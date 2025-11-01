import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import { createRouter, createMemoryHistory } from 'vue-router';
import Criar from '@/Pages/Produtos/Criar.vue';
import api from '@/Services/api.js';

vi.mock('@/Services/api.js');

const mountCriar = async () => {
  const routes = [
    { path: '/dashboard/produtos/criar', component: Criar },
    { path: '/dashboard/produtos', component: { template: '<div>Lista</div>' } }
  ];

  const router = createRouter({
    history: createMemoryHistory(),
    routes
  });

  router.push('/dashboard/produtos/criar');
  await router.isReady();

  render(Criar, {
    global: {
      plugins: [router]
    }
  });

  return { router };
};

describe('Produtos - Criar.vue', () => {

  beforeEach(() => {
    vi.clearAllMocks();
  });

  test('submete o formulário e chama api.post()', async () => {

    api.post.mockResolvedValue({
      data: { success: true }
    });

    const { router } = await mountCriar();

    // Preencher os campos
    await fireEvent.update(screen.getByLabelText(/nome/i), 'Bolo');
    await fireEvent.update(screen.getByLabelText(/preço/i), '15');

    // Submeter
    await fireEvent.submit(screen.getByRole('button', { name: /salvar/i }));

    // ✅ Verifica que api.post foi chamado
    expect(api.post).toHaveBeenCalled();

    // ✅ Verifica que foi para a rota correta
    await waitFor(() => {
      expect(router.currentRoute.value.fullPath).toBe('/dashboard/produtos');
    });
  });

});
