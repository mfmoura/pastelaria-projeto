<template>
  <div>
    <h1>Detalhes do Produto</h1>
    <p><strong>Nome:</strong> {{ produto.nome }}</p>
    <p><strong>Preço:</strong> R$ {{ produto.preco }}</p>
    <p v-if="produto.foto"><strong>Foto:</strong> <br /><img :src="`/storage/${produto.foto}`" width="100" /></p>
    <button @click="voltar">Voltar</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../Services/api.js';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const produto = ref({});

const carregar = async () => {
  try {
    const res = await api.get(`/api/produtos/${route.params.id}`);
    produto.value = res.data.data;
  } catch(err){
    console.error(err);
    alert('Erro ao carregar produto');
  }
}

const voltar = () => router.push('/dashboard/produtos');

onMounted(carregar);
</script>

<style scoped>
p { margin:6px 0; }
img { border-radius:4px; margin-top:5px; }
button { margin-top:10px; background:#f0b400; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
