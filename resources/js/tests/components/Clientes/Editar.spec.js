import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import Editar from '@/Pages/Clientes/Editar.vue';
import api from '@/Services/api.js';
import { createRouter, createWebHistory } from 'vue-router';

// Mock do api.js
vi.mock('@/Services/api.js', () => ({
  default: {
    get: vi.fn(),
    put: vi.fn()
  }
}));

// Router mínimo para o componente
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/clientes', component: { template: '<div></div>' } },
    { path: '/clientes/:id/editar', component: Editar }
  ]
});

describe('Clientes - Editar.vue', () => {
  const clienteMock = {
    id: 1,
    nome: 'João',
    email: 'joao@example.com',
    telefone: '123456789',
    data_nascimento: '2000-05-10',
    endereco: 'Rua A, 123',
    complemento: 'Apto 1',
    bairro: 'Centro',
    cep: '12345-678'
  };

  beforeEach(async () => {
    api.get.mockResolvedValue({ data: { data: clienteMock } });
    api.put.mockResolvedValue({ data: { success: true } });

    // ✅ define a rota antes de montar o componente
    router.push('/clientes/1/editar');
    await router.isReady();
  });

  it('carrega os dados do cliente nos inputs', async () => {
    render(Editar, { global: { plugins: [router] } });

    await waitFor(() => {
      expect(screen.getByLabelText(/nome/i).value).toBe('João');
      expect(screen.getByLabelText(/email/i).value).toBe('joao@example.com');
    });
  });

  it('submete o formulário e chama api.put corretamente', async () => {
    render(Editar, { global: { plugins: [router] } });

    await waitFor(() => expect(screen.getByLabelText(/nome/i).value).toBe('João'));

    await fireEvent.update(screen.getByLabelText(/nome/i), 'João Silva');
    await fireEvent.update(screen.getByLabelText(/email/i), 'joao.silva@example.com');

    await fireEvent.click(screen.getByText(/atualizar/i));

    await waitFor(() => {
      expect(api.put).toHaveBeenCalledWith(
        '/api/clientes/1',
        expect.objectContaining({
          nome: 'João Silva',
          email: 'joao.silva@example.com'
        })
      );
    });
  });

  it('botão cancelar navega para /dashboard/clientes', async () => {
    render(Editar, { global: { plugins: [router] } });

    const pushSpy = vi.spyOn(router, 'push');

    await fireEvent.click(screen.getByText(/cancelar/i));

    expect(pushSpy).toHaveBeenCalledWith('/dashboard/clientes');
  });
});
