<template>
  <div>
    <h1>Editar Produto</h1>
    <form @submit.prevent="atualizar" enctype="multipart/form-data">
      <label for="nome">Nome</label>
      <input id="nome" v-model="form.nome" required />

      <label for="preco">Preço</label>
      <input id="preco" type="number" v-model="form.preco" step="0.01" required />

      <label for="foto">Foto</label>
      <input id="foto" type="file" @change="handleFile" />
      <img v-if="form.foto_url" :src="form.foto_url" width="80" />

      <button type="submit">Atualizar</button>
      <button type="button" @click="voltar">Cancelar</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../../Services/api.js';

const router = useRouter();
const route = useRoute();
const form = reactive({ nome:'', preco:'', foto:null, foto_url:'' });

const carregar = async () => {
  try {
    const res = await api.get(`/api/produtos/${route.params.id}`);
    Object.assign(form, res.data.data);
    form.foto_url = res.data.foto || '';
  } catch(err){
    console.error(err);
    alert('Erro ao carregar produto');
  }
}

const handleFile = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    form.foto = reader.result;
  };
  reader.readAsDataURL(file);
};

const atualizar = async () => {
  try {
    const formData = new FormData();
    formData.append('nome', form.nome);
    formData.append('preco', form.preco);
    if(form.foto) formData.append('foto', form.foto);

    await api.post(`/api/produtos/${route.params.id}?_method=PUT`, formData, { headers:{ 'Content-Type':'multipart/form-data' } });
    router.push('/dashboard/produtos');
  } catch(err){
    console.error(err);
    alert('Erro ao atualizar produto');
  }
}

const voltar = () => router.push('/dashboard/produtos');

onMounted(carregar);
</script>

<style scoped>
label { display:block; margin-top:10px; }
input { width:100%; padding:6px; border-radius:6px; border:1px solid #ccc; margin-top:4px; }
button { margin-top:10px; margin-right:5px; background:#f0b400; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
img { margin-top:8px; border-radius:4px; }
</style>
