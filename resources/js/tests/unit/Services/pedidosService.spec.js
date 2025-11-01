// resources/js/tests/unit/Services/pedidosService.spec.js
import { describe, it, expect, vi, beforeEach } from 'vitest';
import api from '@/Services/api';
import pedidosService from '@/Services/pedidosService';

// Mock da API
vi.mock('@/Services/api', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn()
  }
}));

describe('pedidosService', () => {

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('listar() chama GET /pedidos', async () => {
    api.get.mockResolvedValue({ data: [] });

    await pedidosService.listar();

    expect(api.get).toHaveBeenCalledWith('/pedidos');
  });

  it('criar() envia FormData com os campos corretos e chama POST /pedidos', async () => {
    const data = { nome: 'Pedido Teste', valor: 50 };

    api.post.mockResolvedValue({ data });

    await pedidosService.criar(data);

    expect(api.post).toHaveBeenCalledWith(
      '/pedidos',
      expect.any(FormData)
    );
  });

  it('atualizar() envia FormData e chama POST /pedidos/:id', async () => {
    const id = 3;
    const data = { nome: 'Atualizado' };

    api.post.mockResolvedValue({ data });

    await pedidosService.atualizar(id, data);

    expect(api.post).toHaveBeenCalledWith(
      `/pedidos/${id}`,
      expect.any(FormData)
    );
  });

  it('mostrar() chama GET /pedidos/:id', async () => {
    const id = 10;

    api.get.mockResolvedValue({ data: {} });

    await pedidosService.mostrar(id);

    expect(api.get).toHaveBeenCalledWith(`/pedidos/${id}`);
  });

  it('excluir() chama DELETE /pedidos/:id', async () => {
    const id = 5;

    api.delete.mockResolvedValue({ success: true });

    await pedidosService.excluir(id);

    expect(api.delete).toHaveBeenCalledWith(`/pedidos/${id}`);
  });

});
