import api from '@/Services/api';

describe('api.js', () => {
  beforeEach(() => {
    // limpa localStorage antes de cada teste
    localStorage.clear();
  });

  it('deve ter header Content-Type application/json', () => {
    // Axios por padrão coloca o header 'Content-Type' em defaults.headers
    const contentType =
      api.defaults.headers['Content-Type'] ||
      api.defaults.headers.common?.['Content-Type'] ||
      api.defaults.headers.post?.['Content-Type'];

    expect(contentType).toBe('application/json');
  });

  it('deve adicionar o token Bearer no header Authorization', () => {
    const token = '12345';
    localStorage.setItem('token', token);

    // simula uma requisição com headers vazios
    const config = { headers: {} };
    const modifiedConfig = api.interceptors.request.handlers[0].fulfilled(config);

    expect(modifiedConfig.headers.Authorization).toBe(`Bearer ${token}`);
  });

  it('não adiciona Authorization se não houver token', () => {
    const config = { headers: {} };
    const modifiedConfig = api.interceptors.request.handlers[0].fulfilled(config);

    expect(modifiedConfig.headers.Authorization).toBeUndefined();
  });
});
