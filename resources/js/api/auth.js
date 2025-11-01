import axios from 'axios';

// Cria uma instância do axios com baseURL
const api = axios.create({
    baseURL: 'http://localhost/api',
});

// Interceptor para adicionar o token Bearer em todas as requisições
api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default {
    // Faz login e retorna a resposta do backend
    async login(email, password) {
        const response = await api.post('/login', { email, password });
        return response.data;
    },

    // Faz logout e remove token do localStorage
    async logout() {
        await api.post('/logout');
        localStorage.removeItem('token');
    },

    // Pega o usuário autenticado usando token Bearer
    async me() {
        const response = await api.get('/user');
        return response.data.user || response.data.data; // Ajuste dependendo do seu backend
    },

    // Instância do axios exportada caso queira usar para outras requisições
    apiInstance: api,
};
