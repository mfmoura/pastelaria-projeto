import api from './api';

export default {
  listar: () => api.get('/clientes'),
  criar: data => api.post('/clientes', data),
  atualizar: (id, data) => api.put(`/clientes/${id}`, data),
  mostrar: id => api.get(`/clientes/${id}`),
  excluir: id => api.delete(`/clientes/${id}`)
};
