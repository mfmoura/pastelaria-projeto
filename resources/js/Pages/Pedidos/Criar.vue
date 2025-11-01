<template>
  <div class="pagina">
    <h1>Novo Pedido</h1>

    <!-- Cliente -->
    <div class="form-row">
      <label for="cliente_id">Cliente</label>
      <select id="cliente_id" v-model="pedido.cliente_id">
        <option value="" disabled>— selecione —</option>
        <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nome }}</option>
      </select>
      <div v-if="erroCliente" class="erro">Selecione um cliente</div>
    </div>

    <!-- Produtos -->
    <h2>Produtos</h2>
    <div
      v-for="(item, index) in pedido.produtos"
      :key="index"
      class="produto-box"
    >
      <div class="form-row">
        <label :for="'produto_' + index">Produto</label>
        <select
          :id="'produto_' + index"
          v-model="item.produto_id"
          @change="onSelecionarProduto(index)"
        >
          <option value="" disabled>— selecione —</option>
          <option
            v-for="p in produtosDisponiveis(index)"
            :key="p.id"
            :value="p.id"
          >
            {{ p.nome }}
          </option>
        </select>
      </div>

      <div class="form-row">
        <label :for="'quantidade_' + index">Quantidade</label>
        <input
          :id="'quantidade_' + index"
          type="number"
          v-model.number="item.quantidade"
          min="1"
        />
      </div>

      <div class="form-row">
        <label :for="'preco_' + index">Preço unitário (não editável)</label>
        <div :id="'preco_' + index" class="preco">R$ {{ formatPreco(item.valor_unitario) }}</div>
      </div>

      <div class="form-actions">
        <button type="button" @click="removerProduto(index)">Remover</button>
      </div>
    </div>

    <div style="margin-top:10px;">
      <button type="button" @click="adicionarProduto">Adicionar Produto</button>
    </div>

    <!-- Erro de produto sem seleção -->
    <div v-if="erroProduto" class="erro" style="margin-top:8px;">
      Selecione todos os produtos antes de salvar
    </div>

    <div class="total">
      <strong>Total do Pedido:</strong> R$ {{ formatPreco(total) }}
    </div>

    <div style="margin-top:18px;">
      <button type="button" @click="salvar">Salvar Pedido</button>
      <button type="button" @click="voltar" class="btn-cancel">Cancelar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../../Services/api.js';
import { useRouter } from 'vue-router';

const router = useRouter();

const clientes = ref([]);
const produtos = ref([]);
const pedido = ref({
  cliente_id: '',
  produtos: [] // { produto_id, quantidade, valor_unitario }
});

const selecionados = ref([]);

// Erros
const erroCliente = ref(false);
const erroProduto = ref(false);

const carregar = async () => {
  try {
    const rClientes = await api.get('/api/clientes');
    clientes.value = rClientes.data.data?.data || [];

    const rProdutos = await api.get('/api/produtos');
    produtos.value = rProdutos.data.data?.data || [];
  } catch (e) {
    console.error(e);
    alert('Erro ao carregar clientes/produtos');
  }
};

const adicionarProduto = () => {
  pedido.value.produtos.push({ produto_id: '', quantidade: 1, valor_unitario: 0 });
};

const removerProduto = (index) => {
  pedido.value.produtos.splice(index, 1);
  rebuildSelecionados();
};

const onSelecionarProduto = (index) => {
  rebuildSelecionados();
  const item = pedido.value.produtos[index];
  if (!item.produto_id) {
    item.valor_unitario = 0;
    return;
  }
  const p = produtos.value.find(x => x.id === item.produto_id);
  item.valor_unitario = p ? parseFloat(p.preco) : 0;
  if (!selecionados.value.includes(item.produto_id)) selecionados.value.push(item.produto_id);
};

const rebuildSelecionados = () => {
  selecionados.value = pedido.value.produtos.map(i => i.produto_id).filter(Boolean);
};

const produtosDisponiveis = (index) => {
  return produtos.value.filter(p => {
    const already = selecionados.value.includes(p.id);
    const current = pedido.value.produtos[index].produto_id === p.id;
    return !already || current;
  });
};

const total = computed(() => {
  return pedido.value.produtos.reduce((sum, it) => {
    const q = Number(it.quantidade || 0);
    const v = Number(it.valor_unitario || 0);
    return sum + q * v;
  }, 0);
});

const formatPreco = (v) => (v === null || v === undefined ? '0.00' : parseFloat(v).toFixed(2));

const salvar = async () => {
  erroCliente.value = false;
  erroProduto.value = false;

  if (!pedido.value.cliente_id) {
    erroCliente.value = true;
    return;
  }

  if (pedido.value.produtos.length === 0) {
    erroProduto.value = true;
    return;
  }

  // Verifica se algum produto não foi selecionado
  for (const it of pedido.value.produtos) {
    if (!it.produto_id) {
      erroProduto.value = true;
      return;
    }
  }

  const payload = {
    cliente_id: pedido.value.cliente_id,
    produtos: pedido.value.produtos.map(it => ({
      produto_id: it.produto_id,
      quantidade: it.quantidade,
      valor_unitario: it.valor_unitario
    }))
  };

  try {
    await api.post('/api/pedidos', payload);
    router.push('/dashboard/pedidos');
  } catch (e) {
    console.error(e);
    alert('Erro ao salvar pedido');
  }
};

const voltar = () => router.push('/dashboard/pedidos');

onMounted(carregar);
</script>

<style scoped>
.pagina { padding:16px; }
.form-row { margin-bottom:8px; display:flex; flex-direction:column; }
.form-row label { font-weight:600; margin-bottom:4px; }
.produto-box { border:1px solid #e6e6e6; padding:10px; margin-bottom:10px; border-radius:6px; background:#fff; }
.preco { font-weight:700; padding:6px 0; }
.form-actions { margin-top:8px; }
.total { margin-top:12px; font-size:1.1em; font-weight:700; }
button { background:#f0b400; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
.btn-cancel { background:#aaa; margin-left:8px; }
.erro { color:red; font-size:0.9em; margin-top:4px; }
</style>
