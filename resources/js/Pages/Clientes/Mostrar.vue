<template>
  <div>
    <h1>Detalhes do Cliente</h1>
    <p><strong>Nome:</strong> {{ cliente.nome }}</p>
    <p><strong>Email:</strong> {{ cliente.email }}</p>
    <p><strong>Telefone:</strong> {{ cliente.telefone }}</p>
    <p><strong>Data de Nascimento:</strong> {{ cliente.data_nascimento }}</p>
    <p><strong>Endereço:</strong> {{ cliente.endereco }}</p>
    <p><strong>Complemento:</strong> {{ cliente.complemento }}</p>
    <p><strong>Bairro:</strong> {{ cliente.bairro }}</p>
    <p><strong>CEP:</strong> {{ cliente.cep }}</p>
    <button @click="voltar">Voltar</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../Services/api.js';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const cliente = ref({});

const carregar = async () => {
  try {
    const res = await api.get(`/api/clientes/${route.params.id}`);
    cliente.value = res.data.data;
  } catch(err){
    console.error(err);
    alert('Erro ao carregar cliente');
  }
}

const voltar = () => router.push('/dashboard/clientes');

onMounted(carregar);
</script>

<style scoped>
p { margin:6px 0; }
button { margin-top:10px; background:#f0b400; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
