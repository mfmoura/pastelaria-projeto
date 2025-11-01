import api from './api';

export default {
  listar: () => api.get('/pedidos'),
  criar: data => {
    const fd = new FormData();
    Object.keys(data).forEach(k => fd.append(k, data[k]));
    return api.post('/pedidos', fd);
  },
  atualizar: (id, data) => {
    const fd = new FormData();
    Object.keys(data).forEach(k => fd.append(k, data[k]));
    return api.post(`/pedidos/${id}`, fd);
  },
  mostrar: id => api.get(`/pedidos/${id}`),
  excluir: id => api.delete(`/pedidos/${id}`)
};
