<template>
  <div class="pagina" v-if="pedido">
    <h1>Pedido #{{ pedido.id }}</h1>

    <p><strong>Cliente:</strong> {{ pedido.cliente?.nome || '—' }}</p>

    <h2>Produtos</h2>
    <ul>
      <li v-for="p in pedido.produtos" :key="p.id">
        {{ p.nome }} — Quantidade: {{ p.pivot?.quantidade || 0 }} — Valor Unitário: R$ {{ formatPreco(p.pivot?.valor_unitario || p.preco) }}
      </li>
    </ul>

    <div class="total">
      <strong>Total do Pedido:</strong> R$ {{ formatPreco(total) }}
    </div>

    <button @click="voltar">Voltar</button>
  </div>

  <div v-else>
    <p>Carregando pedido...</p>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../../Services/api.js';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const pedido = ref(null);

const carregar = async () => {
  try {
    const res = await api.get(`api/pedidos/${route.params.id}`);
    pedido.value = res.data.data; // pega o pedido completo
  } catch (e) {
    console.error(e);
    alert('Erro ao carregar pedido');
  }
};

const total = computed(() => {
  if (!pedido.value || !pedido.value.produtos) return 0;
  return pedido.value.produtos.reduce((sum, p) => {
    const q = p.pivot?.quantidade || 0;
    const v = parseFloat(p.pivot?.valor_unitario || p.preco) || 0;
    return sum + q * v;
  }, 0);
});

const formatPreco = (v) => (v === null || v === undefined ? '0.00' : parseFloat(v).toFixed(2));

const voltar = () => router.push('/dashboard/pedidos');

onMounted(carregar);
</script>

<style scoped>
.pagina { padding:16px; }
.total { margin-top:12px; font-weight:700; font-size:1.2em; }
button { margin-top:10px; background:#f0b400; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
