import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import { createRouter, createMemoryHistory } from 'vue-router';
import Editar from '@/Pages/Produtos/Editar.vue';
import api from '@/Services/api.js';

vi.mock('@/Services/api.js');

const mountEditar = async () => {
  const routes = [
    { path: '/dashboard/produtos/:id/editar', component: Editar }
  ];

  const router = createRouter({
    history: createMemoryHistory(),
    routes
  });

  router.push('/dashboard/produtos/1/editar');
  await router.isReady();

  render(Editar, {
    global: {
      plugins: [router]
    }
  });

  return { router };
};

describe('Produtos - Editar.vue', () => {

  beforeEach(() => {
    vi.clearAllMocks();
  });

  test('carrega dados e preenche o formulário', async () => {

    // ✅ Mock completo da API
    api.get.mockResolvedValue({
      data: {
        data: {
          id: 1,
          nome: 'Bolo',
          preco: 12.5
        },
        foto: null
      }
    });

    await mountEditar();

    // ✅ Aguarda Vue preencher o formulário
    await waitFor(() => {
      expect(screen.getByLabelText(/nome/i).value).toBe('Bolo');
      expect(screen.getByLabelText(/preço/i).value).toBe('12.5');
    });
  });

  test('submete o formulário e envia dados para o produtosService', async () => {

    // Mock da carga inicial
    api.get.mockResolvedValue({
      data: {
        data: {
          id: 1,
          nome: 'Bolo',
          preco: 12.5
        },
        foto: null
      }
    });

    // Mock do POST (update)
    api.post.mockResolvedValue({
      data: { success: true }
    });

    const { router } = await mountEditar();

    // Digita valores
    await waitFor(() => {
      expect(screen.getByLabelText(/nome/i).value).toBe('Bolo');
    });

    await fireEvent.update(screen.getByLabelText(/nome/i), 'Bolo de Chocolate');
    await fireEvent.update(screen.getByLabelText(/preço/i), '15.90');

    // Submete o form
    await fireEvent.submit(screen.getByRole('button', { name: /atualizar/i }));

    expect(api.post).toHaveBeenCalled();

    // Verifica se redirecionou
    await waitFor(() => {
      expect(router.currentRoute.value.fullPath).toBe('/dashboard/produtos');
    });
  });

});
