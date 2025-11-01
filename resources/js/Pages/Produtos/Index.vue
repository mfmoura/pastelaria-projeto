<template>
  <div>
    <h1>Produtos</h1>
    <button @click="criar">Novo Produto</button>

    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Preço</th>
          <th>Foto</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <!-- Linha de aviso caso não haja produtos -->
        <tr v-if="produtos.length === 0">
          <td colspan="4" style="text-align:center;">Nenhum produto encontrado</td>
        </tr>

        <!-- Produtos reais -->
        <tr v-for="p in produtos" :key="p.id">
          <td>{{ p.nome }}</td>
          <td>R$ {{ parseFloat(p.preco).toFixed(2) }}</td>
          <td>
            <img v-if="p.foto" :src="`/storage/${p.foto}`" alt="Foto do produto" width="50" />
          </td>
          <td>
            <button @click="mostrar(p.id)">Mostrar</button>
            <button @click="editar(p.id)">Editar</button>
            <button @click="excluir(p.id)">Excluir</button>
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
const produtos = ref([]);

// Carrega produtos da API paginada
const carregar = async () => {
  try {
    const res = await api.get('/api/produtos');
    produtos.value = res.data.data.data || [];
  } catch(err){
    console.error(err);
    alert('Erro ao carregar produtos');
  }
}

// Navegação
const criar = () => router.push('/dashboard/produtos/criar');
const editar = (id) => router.push(`/dashboard/produtos/${id}/editar`);
const mostrar = (id) => router.push(`/dashboard/produtos/${id}`);

// Excluir produto
const excluir = async (id) => {
  if(confirm('Deseja realmente excluir este produto?')){
    try {
      await api.delete(`/api/produtos/${id}`);
      produtos.value = produtos.value.filter(p => p.id !== id);
    } catch(err){
      console.error(err);
      alert('Erro ao excluir produto');
    }
  }
}

onMounted(carregar);
</script>

<style scoped>
table { width:100%; border-collapse: collapse; margin-top:10px; }
th, td { border:1px solid #ccc; padding:6px; text-align:left; }
img { border-radius:4px; }
button { margin-right:5px; background:#f0b400; color:white; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
