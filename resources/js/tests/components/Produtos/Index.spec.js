// resources/js/tests/components/Produtos/Index.spec.js
import { render, fireEvent, screen } from '@testing-library/vue';
import { vi } from 'vitest';
import Index from '@/Pages/Produtos/Index.vue';
import api from '@/Services/api';
import router from '@/Router';

// Mock da API
vi.mock('@/Services/api', () => ({
  default: {
    get: vi.fn(),
    delete: vi.fn()
  }
}));

describe('Produtos - Index.vue', () => {
  const produtoMock = { id: 1, nome: 'Produto Teste', preco: 9.99, foto: null };

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('mostra mensagem quando não há produtos', async () => {
    api.get.mockResolvedValue({
      data: { data: { data: [] } }
    });

    render(Index, { global: { plugins: [router] } });

    expect(await screen.findByText('Nenhum produto encontrado')).toBeTruthy();
  });

  it('renderiza produtos corretamente', async () => {
    api.get.mockResolvedValue({
      data: { data: { data: [produtoMock] } }
    });

    render(Index, { global: { plugins: [router] } });

    expect(await screen.findByText('Produto Teste')).toBeTruthy();
    expect(screen.getByText('R$ 9.99')).toBeTruthy();
    expect(screen.getByText('Mostrar')).toBeTruthy();
    expect(screen.getByText('Editar')).toBeTruthy();
    expect(screen.getByText('Excluir')).toBeTruthy();
  });

  it('botão Excluir chama api.delete() e remove produto da lista', async () => {
    api.get.mockResolvedValue({ data: { data: { data: [produtoMock] } } });
    api.delete.mockResolvedValue({ success: true });

    render(Index, { global: { plugins: [router] } });

    const button = await screen.findByText('Excluir');
    // Simula confirmar a exclusão
    vi.spyOn(window, 'confirm').mockReturnValueOnce(true);

    await fireEvent.click(button);

    expect(api.delete).toHaveBeenCalledWith('/api/produtos/1');
    // Produto removido da lista
    expect(screen.queryByText('Produto Teste')).toBeNull();
  });

  it('botão Mostrar existe e pode ser clicado', async () => {
    api.get.mockResolvedValue({ data: { data: { data: [produtoMock] } } });

    render(Index, { global: { plugins: [router] } });

    const button = await screen.findByText('Mostrar');
    await fireEvent.click(button);

    // Em teste unitário, não navegamos de fato, apenas garante que existe e não quebra
    expect(button).toBeTruthy();
  });

  it('botão Editar existe e pode ser clicado', async () => {
    api.get.mockResolvedValue({ data: { data: { data: [produtoMock] } } });

    render(Index, { global: { plugins: [router] } });

    const button = await screen.findByText('Editar');
    await fireEvent.click(button);

    expect(button).toBeTruthy();
  });

  it('botão Novo Produto existe e pode ser clicado', async () => {
    api.get.mockResolvedValue({ data: { data: { data: [] } } });

    render(Index, { global: { plugins: [router] } });

    const button = screen.getByText('Novo Produto');
    await fireEvent.click(button);

    expect(button).toBeTruthy();
  });
});
