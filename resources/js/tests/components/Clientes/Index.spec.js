import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import Index from '@/Pages/Clientes/Index.vue';
import api from '@/Services/api.js';
import { vi } from 'vitest';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('@/Services/api.js');

describe('Clientes - Index.vue', () => {
  const clientesMock = [
    { id: 1, nome: 'João', email: 'joao@email.com', telefone: '123456789' }
  ];

  const router = createRouter({
    history: createWebHistory(),
    routes: []
  });

  beforeEach(() => {
    api.get.mockResolvedValue({ data: { data: { data: clientesMock } } });
    api.delete.mockResolvedValue({ data: { success: true } });
  });

  it('botão Excluir chama api.delete e remove o cliente', async () => {
    render(Index, { global: { plugins: [router] } });

    await router.isReady();

    // espera carregar cliente
    await waitFor(() => screen.getByText('João'));

    // mock confirm para retornar true
    vi.spyOn(window, 'confirm').mockReturnValue(true);

    const btnExcluir = screen.getByText(/excluir/i);
    await fireEvent.click(btnExcluir);

    // espera o cliente ser removido da tabela
    await waitFor(() => {
      expect(api.delete).toHaveBeenCalledWith('/api/clientes/1');
      expect(screen.queryByText('João')).toBeNull();
    });
  });
});
