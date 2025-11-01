<template>
  <div>
    <h1>Editar Cliente</h1>
    <form @submit.prevent="atualizar">
      <label for="nome">Nome</label>
      <input id="nome" v-model="form.nome" required />
      
      <label for="email">Email</label>
      <input id="email" type="email" v-model="form.email" required />
      
      <label for="telefone">Telefone</label>
      <input id="telefone" v-model="form.telefone" />

      <label for="data_nascimento">Data de Nascimento</label>
      <input id="data_nascimento" type="date" v-model="form.data_nascimento" />

      <label for="endereco">Endereço</label>
      <input id="endereco" v-model="form.endereco" />
      
      <label for="complemento">Complemento</label>
      <input id="complemento" v-model="form.complemento" />

      <label for="bairro">Bairro</label>
      <input id="bairro" v-model="form.bairro" />

      <label for="cep">CEP</label>
      <input id="cep" v-model="form.cep" />

      <button type="submit">Atualizar</button>
      <button type="button" @click="voltar">Cancelar</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import api from '../../Services/api.js';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const form = reactive({
  nome: '',
  email: '',
  telefone: '',
  data_nascimento: '',
  endereco: '',
  complemento: '',
  bairro: '',
  cep: ''
});

const carregar = async () => {
  try {
    const res = await api.get(`/api/clientes/${route.params.id}`);
    Object.assign(form, res.data.data);
  } catch(err){
    console.error(err);
    alert('Erro ao carregar cliente');
  }
}

const atualizar = async () => {
  try {
    await api.put(`/api/clientes/${route.params.id}`, form);
    router.push('/dashboard/clientes');
  } catch(err){
    console.error(err);
    alert('Erro ao atualizar cliente');
  }
}

const voltar = () => router.push('/dashboard/clientes');

onMounted(carregar);
</script>

<style scoped>
label { display:block; margin-top:10px; }
input { width:100%; padding:6px; border-radius:6px; border:1px solid #ccc; margin-top:4px; }
button { margin-top:10px; margin-right:5px; background:#f0b400; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
