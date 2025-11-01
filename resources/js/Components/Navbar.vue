<template>
  <nav class="navbar">
    <button @click="$router.push('/dashboard/clientes')">Clientes</button>
    <button @click="$router.push('/dashboard/produtos')">Produtos</button>
    <button @click="$router.push('/dashboard/pedidos')">Pedidos</button>
    <button @click="logout">Sair</button>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
  localStorage.removeItem('token');
  router.push('/login');
}
</script>

<style scoped>
.navbar { margin-bottom:20px; }
button { background:#f0b400; color:white; padding:6px 12px; border:none; border-radius:6px; margin-right:5px; cursor:pointer; }
button:hover { background:#d89f00; }
</style>
