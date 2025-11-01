<template>
  <div class="pagina login">
    <h1>Login</h1>
    <form @submit.prevent="entrar">
      <label>Email</label>
      <input type="email" v-model="form.email" required />

      <label>Senha</label>
      <input type="password" v-model="form.password" required />

      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../Services/api.js';

const router = useRouter();
const form = reactive({ email: '', password: '' });

const entrar = async () => {
  try {
    const res = await api.post('/api/login', form);
    if(res.data.token){
      localStorage.setItem('token', res.data.token);
      router.push('/dashboard/clientes');
    } else {
      alert('Credenciais inválidas');
    }
  } catch(err){
    console.error(err);
    alert('Erro ao fazer login');
  }
}
</script>

<style scoped>
.pagina.login { max-width:400px; margin:50px auto; padding:20px; background:#fff0f5; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
label { display:block; margin-top:10px; }
input { width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-top:5px; }
button { margin-top:15px; background:#f0b400; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
