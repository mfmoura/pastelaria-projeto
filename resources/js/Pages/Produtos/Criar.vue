<template>
  <div>
    <h1>Novo Produto</h1>
    <form @submit.prevent="salvar" enctype="multipart/form-data">
      <label for="nome">Nome</label>
      <input id="nome" v-model="form.nome" required />

      <label for="preco">Preço</label>
      <input id="preco" type="number" v-model="form.preco" step="0.01" required />

      <label for="foto">Foto</label>
      <input id="foto" type="file" @change="handleFile" />

      <button type="submit">Salvar</button>
      <button type="button" @click="voltar">Cancelar</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import api from '../../Services/api.js';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = reactive({ nome:'', preco:'', foto:null });

const handleFile = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    form.foto = reader.result;
  };
  reader.readAsDataURL(file);
};

const salvar = async () => {
  try {
    const formData = new FormData();
    formData.append('nome', form.nome);
    formData.append('preco', form.preco);
    formData.append('foto', form.foto);

    await api.post('/api/produtos', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
    router.push('/dashboard/produtos');
  } catch(err){
    console.error(err);
    alert('Erro ao salvar produto');
  }
}

const voltar = () => router.push('/dashboard/produtos');
</script>

<style scoped>
label { display:block; margin-top:10px; }
input { width:100%; padding:6px; border-radius:6px; border:1px solid #ccc; margin-top:4px; }
button { margin-top:10px; margin-right:5px; background:#f0b400; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
