<template>
  <div class="pagina">
    <h1>Pedidos</h1>

    <button class="btn-novo" @click="novo">Novo Pedido</button>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Produtos</th>
          <th>Total</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pedido in pedidos" :key="pedido.id">
          <td>{{ pedido.id }}</td>
          <td>{{ pedido.cliente?.nome || '—' }}</td>
          <td>
            <ul>
              <li v-for="p in pedido.produtos" :key="p.id">
                {{ p.nome }} x {{ p.pivot.quantidade }}
              </li>
            </ul>
          </td>
          <td>R$ {{ formatPreco(totalPedido(pedido)) }}</td>
          <td>
            <button @click="editar(pedido.id)">Editar</button>
            <button @click="mostrar(pedido.id)">Ver</button>
            <button @click="excluir(pedido.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../Services/api.js';
import { useRouter } from 'vue-router';

const router = useRouter();
const pedidos = ref([]);

const carregar = async () => {
  try {
    const res = await api.get('api/pedidos');
    pedidos.value = res.data.data?.data || [];
  } catch (e) {
    console.error(e);
    alert('Erro ao carregar pedidos');
  }
};

const totalPedido = (pedido) => {
  return pedido.produtos?.reduce((sum, p) => {
    const quantidade = p.pivot?.quantidade || 0;
    const valor = parseFloat(p.pivot?.valor_unitario) || parseFloat(p.preco) || 0;
    return sum + quantidade * valor;
  }, 0);
};

const formatPreco = (v) => (v === null || v === undefined ? '0.00' : parseFloat(v).toFixed(2));

const novo = () => router.push('/dashboard/pedidos/criar');
const editar = (id) => router.push(`/dashboard/pedidos/${id}/editar`);
const mostrar = (id) => router.push(`/dashboard/pedidos/${id}`);
const excluir = async (id) => {
  if (!confirm('Confirma exclusão?')) return;
  try {
    await api.delete(`api/pedidos/${id}`);
    pedidos.value = pedidos.value.filter(p => p.id !== id);
  } catch (e) {
    console.error(e);
    alert('Erro ao excluir pedido');
  }
};

onMounted(carregar);
</script>

<style scoped>
.pagina { padding:16px; }
.btn-novo { background:#28a745; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; margin-bottom:12px; }
.btn-novo:hover { background:#218838; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #ddd; padding:8px; vertical-align: top; }
th { background:#f0b400; color:#fff; }
button { margin-right:4px; background:#f0b400; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
