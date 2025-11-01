import { createRouter, createWebHistory } from 'vue-router';
import Login from '../Pages/Login.vue';
import Dashboard from '../Pages/Dashboard.vue';
import ClientesIndex from '../Pages/Clientes/Index.vue';
import ClientesCriar from '../Pages/Clientes/Criar.vue';
import ClientesEditar from '../Pages/Clientes/Editar.vue';
import ClientesMostrar from '../Pages/Clientes/Mostrar.vue';
import ProdutosIndex from '../Pages/Produtos/Index.vue';
import ProdutosCriar from '../Pages/Produtos/Criar.vue';
import ProdutosEditar from '../Pages/Produtos/Editar.vue';
import ProdutosMostrar from '../Pages/Produtos/Mostrar.vue';
import PedidosIndex from '../Pages/Pedidos/Index.vue';
import PedidosCriar from '../Pages/Pedidos/Criar.vue';
import PedidosEditar from '../Pages/Pedidos/Editar.vue';
import PedidosMostrar from '../Pages/Pedidos/Mostrar.vue';
// Produtos e Pedidos semelhantes

const routes = [
  { path:'/', redirect:'/login' },
  { path:'/login', component: Login },
  { path:'/dashboard', component: Dashboard, children:[
      { path:'clientes', component: ClientesIndex },
      { path:'clientes/criar', component: ClientesCriar },
      { path:'clientes/:id/editar', component: ClientesEditar },
      { path:'clientes/:id', component: ClientesMostrar },
      { path:'produtos', component: ProdutosIndex },
      { path:'produtos/criar', component: ProdutosCriar },
      { path:'produtos/:id/editar', component: ProdutosEditar },
      { path:'produtos/:id', component: ProdutosMostrar },
      { path:'pedidos', component: PedidosIndex },
      { path:'pedidos/criar', component: PedidosCriar },
      { path:'pedidos/:id/editar', component: PedidosEditar },
      { path:'pedidos/:id', component: PedidosMostrar },
    ]
  },
];

const router = createRouter({
  history:createWebHistory(),
  routes
});

// Guard para rotas autenticadas
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if(to.path.startsWith('/dashboard') && !token){
    next('/login');
  } else next();
});

export default router;
