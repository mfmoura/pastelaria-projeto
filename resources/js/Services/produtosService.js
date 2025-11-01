import api from './api';

export default {
  listar: () => api.get('/produtos'),
  criar: data => api.post('/produtos', data),
  atualizar: (id, data) => api.put(`/produtos/${id}`, data),
  mostrar: id => api.get(`/produtos/${id}`),
  excluir: id => api.delete(`/produtos/${id}`)
};
