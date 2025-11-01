import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import Criar from '@/Pages/Clientes/Criar.vue';
import api from '@/Services/api.js';
import { createRouter, createWebHistory } from 'vue-router';

// Mock do api
vi.mock('@/Services/api.js', () => ({
  default: {
    post: vi.fn()
  }
}));

// Router mínimo só para testes
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/clientes', name: 'clientes.index', component: { template: '<div></div>' } },
    { path: '/clientes/criar', name: 'clientes.criar', component: { template: '<div></div>' } }
  ]
});

describe('Clientes - Criar.vue', () => {
  beforeEach(() => {
    api.post.mockReset();
    api.post.mockResolvedValue({ data: { success: true } });
  });

  it('submete o formulário e chama api.post com os dados corretos', async () => {
    render(Criar, { global: { plugins: [router] } });

    await fireEvent.update(screen.getByLabelText(/nome/i), 'Teste');
    await fireEvent.update(screen.getByLabelText(/email/i), 'teste@example.com');
    await fireEvent.update(screen.getByLabelText(/telefone/i), '123456789');
    await fireEvent.update(screen.getByLabelText(/data de nascimento/i), '2000-01-01');
    await fireEvent.update(screen.getByLabelText(/endereço/i), 'Rua Exemplo');
    await fireEvent.update(screen.getByLabelText(/complemento/i), 'Apto 1');
    await fireEvent.update(screen.getByLabelText(/bairro/i), 'Centro');
    await fireEvent.update(screen.getByLabelText(/cep/i), '12345000');

    await fireEvent.click(screen.getByText(/salvar/i));

    await waitFor(() => {
      expect(api.post).toHaveBeenCalledWith(
        '/api/clientes',
        expect.objectContaining({
          nome: 'Teste',
          email: 'teste@example.com',
          telefone: '123456789',
          data_nascimento: '2000-01-01',
          endereco: 'Rua Exemplo',
          complemento: 'Apto 1',
          bairro: 'Centro',
          cep: '12345000'
        })
      );
    });
  });
});
