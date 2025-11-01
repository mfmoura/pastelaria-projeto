<template>
  <div>
    <h1>Clientes</h1>
    <button @click="criar">Novo Cliente</button>

    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in clientes" :key="c.id">
          <td>{{ c.nome }}</td>
          <td>{{ c.email }}</td>
          <td>{{ c.telefone }}</td>
          <td>
            <button @click="mostrar(c.id)">Mostrar</button>
            <button @click="editar(c.id)">Editar</button>
            <button @click="excluir(c.id)">Excluir</button>
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
const clientes = ref([]);

// Carrega a lista de clientes
const carregar = async () => {
  try {
    const res = await api.get('/api/clientes');
    clientes.value = res.data.data.data;
  } catch(err){
    console.error(err);
    alert('Erro ao carregar clientes');
  }
}

// Navegação
const criar = () => router.push('/dashboard/clientes/criar');
const editar = (id) => router.push(`/dashboard/clientes/${id}/editar`);
const mostrar = (id) => router.push(`/dashboard/clientes/${id}`);
const excluir = async (id) => {
  if(confirm('Deseja realmente excluir este cliente?')){
    try {
      await api.delete(`/api/clientes/${id}`);
      clientes.value = clientes.value.filter(c => c.id !== id);
    } catch(err){
      console.error(err);
      alert('Erro ao excluir cliente');
    }
  }
}

onMounted(carregar);
</script>

<style scoped>
table { width:100%; border-collapse: collapse; margin-top: 10px; }
th, td { border:1px solid #ccc; padding:6px; text-align:left; }
button { margin-right:5px; background:#f0b400; color:white; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
