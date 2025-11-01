<template>
  <div class="pagina">
    <h1>Editar Pedido</h1>

    <!-- Seleção de cliente -->
    <div class="form-row">
      <label for="cliente_id">Cliente</label>
      <select id="cliente_id" v-model="pedido.cliente_id">
        <option value="" disabled>— selecione —</option>
        <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nome }}</option>
      </select>
      <p v-if="erros.cliente_id" class="erro">{{ erros.cliente_id }}</p>
    </div>

    <h2>Produtos</h2>

    <div v-for="(item, index) in pedido.produtos" :key="index" class="produto-box">
      <div class="form-row">
        <label :for="'produto_id_' + index">Produto</label>
        <select
          :id="'produto_id_' + index"
          v-model="item.produto_id"
          @change="onSelecionarProduto(index)"
        >
          <option value="" disabled>— selecione —</option>
          <option v-for="p in produtosDisponiveis(index)" :key="p.id" :value="p.id">
            {{ p.nome }}
          </option>
        </select>
        <p v-if="erros.produtos[index]?.produto_id" class="erro">
          {{ erros.produtos[index].produto_id }}
        </p>
      </div>

      <div class="form-row">
        <label :for="'quantidade_' + index">Quantidade</label>
        <input
          :id="'quantidade_' + index"
          type="number"
          v-model.number="item.quantidade"
          min="1"
        />
        <p v-if="erros.produtos[index]?.quantidade" class="erro">
          {{ erros.produtos[index].quantidade }}
        </p>
      </div>

      <div class="form-row">
        <label :for="'valor_unitario_' + index">Preço unitário</label>
        <div :id="'valor_unitario_' + index" class="preco">
          R$ {{ formatPreco(item.valor_unitario) }}
        </div>
      </div>

      <div class="form-actions">
        <button type="button" @click="removerProduto(index)">Remover</button>
      </div>
    </div>

    <div style="margin-top:10px;">
      <button type="button" @click="adicionarProduto">Adicionar Produto</button>
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
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

const clientes = ref([]);
const produtos = ref([]);
const pedido = ref({ cliente_id: '', produtos: [] });

const erros = ref({
  cliente_id: '',
  produtos: []
});

const selecionados = ref([]);

const formatPreco = (v) => (v === null || v === undefined ? '0.00' : parseFloat(v).toFixed(2));

const total = computed(() =>
  pedido.value.produtos.reduce((sum, it) => sum + (it.quantidade || 0) * (it.valor_unitario || 0), 0)
);

const rebuildSelecionados = () => {
  selecionados.value = pedido.value.produtos.map((i) => i.produto_id).filter(Boolean);
};

const produtosDisponiveis = (index) =>
  produtos.value.filter((p) => {
    const already = selecionados.value.includes(p.id);
    const current = pedido.value.produtos[index].produto_id === p.id;
    return !already || current;
  });

const adicionarProduto = () => {
  pedido.value.produtos.push({ produto_id: '', quantidade: 1, valor_unitario: 0 });
  erros.value.produtos.push({});
};

const removerProduto = (index) => {
  pedido.value.produtos.splice(index, 1);
  erros.value.produtos.splice(index, 1);
  rebuildSelecionados();
};

const onSelecionarProduto = (index) => {
  rebuildSelecionados();
  const item = pedido.value.produtos[index];
  const p = produtos.value.find((x) => x.id === item.produto_id);
  item.valor_unitario = p ? Number(p.preco) : 0;
};

const validar = () => {
  erros.value = { cliente_id: '', produtos: [] };
  let ok = true;

  if (!pedido.value.cliente_id) {
    erros.value.cliente_id = 'Selecione um cliente.';
    ok = false;
  }

  if (!pedido.value.produtos.length) {
    erros.value.produtos = [{ produto_id: 'Adicione ao menos um produto.' }];
    ok = false;
  }

  pedido.value.produtos.forEach((it, i) => {
    erros.value.produtos[i] = {};

    if (!it.produto_id) {
      erros.value.produtos[i].produto_id = 'Selecione um produto.';
      ok = false;
    }
    if (!it.quantidade || it.quantidade <= 0) {
      erros.value.produtos[i].quantidade = 'Quantidade inválida.';
      ok = false;
    }
  });

  return ok;
};

const salvar = async () => {
  if (!validar()) return;

  const payload = {
    cliente_id: pedido.value.cliente_id,
    produtos: pedido.value.produtos.map((it) => ({
      produto_id: it.produto_id,
      quantidade: it.quantidade,
      valor_unitario: it.valor_unitario
    }))
  };

  try {
    await api.put(`/api/pedidos/${route.params.id}`, payload);
    router.push('/dashboard/pedidos');
  } catch (e) {
    console.error(e);
  }
};

const voltar = () => router.push('/dashboard/pedidos');

const carregar = async () => {
  try {
    const [rClientes, rProdutos] = await Promise.all([
      api.get('/api/clientes'),
      api.get('/api/produtos')
    ]);

    clientes.value = rClientes.data.data.data || [];
    produtos.value = rProdutos.data.data.data || [];

    const rPedido = await api.get(`/api/pedidos/${route.params.id}`);
    const p = rPedido.data.data;

    pedido.value.cliente_id = p.cliente.id;
    pedido.value.produtos = p.produtos.map((prod) => ({
      produto_id: prod.id,
      quantidade: prod.pivot.quantidade,
      valor_unitario: Number(prod.pivot.valor_unitario)
    }));

    erros.value.produtos = p.produtos.map(() => ({}));

    rebuildSelecionados();
  } catch (e) {
    console.error(e);
  }
};

onMounted(carregar);
</script>

<style scoped>
.pagina { padding:16px; }
.form-row { margin-bottom:8px; display:flex; flex-direction:column; }
.form-row label { font-weight:600; margin-bottom:4px; }
.erro { color:#d00; font-size: 13px; margin-top: 4px; }
.produto-box { border:1px solid #e6e6e6; padding:10px; margin-bottom:10px; border-radius:6px; background:#fff; }
.preco { font-weight:700; padding:6px 0; }
.form-actions { margin-top:8px; }
.total { margin-top:12px; font-size:1.1em; font-weight:700; }
button { background:#f0b400; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; }
button:hover { background:#d89f00; }
.btn-cancel { background:#aaa; margin-left:8px; }
</style>
