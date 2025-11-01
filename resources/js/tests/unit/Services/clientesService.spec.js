// resources/js/tests/unit/Services/clientesService.spec.js
import { describe, it, expect, vi, beforeEach } from 'vitest';
import api from '@/Services/api';
import clientesService from '@/Services/clientesService';

// Mock da API
vi.mock('@/Services/api', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn(),
  },
}));

describe('clientesService', () => {
  beforeEach(() => {
    vi.clearAllMocks(); // limpa mocks antes de cada teste
  });

  it('deve retornar a lista de clientes', async () => {
    const mockClientes = [
      { id: 1, nome: 'João' },
      { id: 2, nome: 'Maria' },
    ];

    api.get.mockResolvedValue({ data: { data: mockClientes } });

    const resultado = await clientesService.listar();

    expect(api.get).toHaveBeenCalledWith('/clientes');
    expect(resultado.data.data).toEqual(mockClientes);
  });

  it('deve criar um novo cliente', async () => {
    const novoCliente = { nome: 'Carlos' };
    const respostaAPI = { data: { data: { id: 3, nome: 'Carlos' } } };

    api.post.mockResolvedValue(respostaAPI);

    const resultado = await clientesService.criar(novoCliente);

    expect(api.post).toHaveBeenCalledWith('/clientes', novoCliente);
    expect(resultado.data.data).toEqual(respostaAPI.data.data);
  });

  it('deve atualizar um cliente existente', async () => {
    const clienteAtualizado = { id: 1, nome: 'João Atualizado' };
    const respostaAPI = { data: { data: clienteAtualizado } };

    api.put.mockResolvedValue(respostaAPI);

    const resultado = await clientesService.atualizar(clienteAtualizado.id, clienteAtualizado);

    expect(api.put).toHaveBeenCalledWith(`/clientes/${clienteAtualizado.id}`, clienteAtualizado);
    expect(resultado.data.data).toEqual(clienteAtualizado);
  });

  it('deve mostrar um cliente existente', async () => {
    const id = 10;
    const respostaAPI = { data: { data: { id, nome: 'Cliente X' } } };

    api.get.mockResolvedValue(respostaAPI);

    const resultado = await clientesService.mostrar(id);

    expect(api.get).toHaveBeenCalledWith(`/clientes/${id}`);
    expect(resultado.data.data).toEqual(respostaAPI.data.data);
  });

  it('deve excluir um cliente', async () => {
    const idCliente = 1;
    const respostaAPI = { data: { status: 200 } };

    api.delete.mockResolvedValue(respostaAPI);

    const resultado = await clientesService.excluir(idCliente);

    expect(api.delete).toHaveBeenCalledWith(`/clientes/${idCliente}`);
    expect(resultado.data.status).toEqual(200);
  });
});
