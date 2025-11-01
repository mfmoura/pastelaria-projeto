import { describe, it, expect, vi, beforeEach } from 'vitest';
import produtosService from '../../../Services/produtosService';
import api from '../../../Services/api';

// Mock da API
vi.mock('../../../Services/api', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn()
  }
}));

describe('produtosService', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('listar() chama GET /produtos', async () => {
    api.get.mockResolvedValue({ data: [] });

    const resultado = await produtosService.listar();

    expect(api.get).toHaveBeenCalledWith('/produtos');
    expect(resultado).toEqual({ data: [] });
  });

  it('criar() chama POST /produtos com os dados corretos', async () => {
    const data = { nome: 'Pastel', preco: 12.50 };

    api.post.mockResolvedValue({ data });

    const resultado = await produtosService.criar(data);

    expect(api.post).toHaveBeenCalledWith('/produtos', data);
    expect(resultado).toEqual({ data });
  });

  it('atualizar() chama PUT /produtos/:id', async () => {
    const produtoAtualizado = { id: 5, nome: 'Coxinha', preco: 8.00 };
    const respostaAPI = { data: produtoAtualizado };

    api.put.mockResolvedValue(respostaAPI);

    const resultado = await produtosService.atualizar(produtoAtualizado.id, produtoAtualizado);

    expect(api.put).toHaveBeenCalledWith(`/produtos/${produtoAtualizado.id}`, produtoAtualizado);
    expect(resultado).toEqual(respostaAPI);
  });

  it('mostrar() chama GET /produtos/:id', async () => {
    const id = 3;
    const respostaAPI = { data: { id, nome: 'Pastel' } };

    api.get.mockResolvedValue(respostaAPI);

    const resultado = await produtosService.mostrar(id);

    expect(api.get).toHaveBeenCalledWith(`/produtos/${id}`);
    expect(resultado).toEqual(respostaAPI);
  });

  it('excluir() chama DELETE /produtos/:id', async () => {
    const id = 9;
    const respostaAPI = { success: true };

    api.delete.mockResolvedValue(respostaAPI);

    const resultado = await produtosService.excluir(id);

    expect(api.delete).toHaveBeenCalledWith(`/produtos/${id}`);
    expect(resultado).toEqual(respostaAPI);
  });
});
