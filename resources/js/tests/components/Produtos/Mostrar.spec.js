import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, waitFor } from '@testing-library/vue';
import Mostrar from '../../../Pages/Produtos/Mostrar.vue';
import api from '../../../Services/api';
import { createRouter, createWebHistory } from 'vue-router';

// Mock da API
vi.mock('../../../Services/api', () => ({
  default: {
    get: vi.fn()
  }
}));

// Mock do router
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/dashboard/produtos/:id', component: Mostrar }
  ]
});

describe('Produtos - Mostrar.vue', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('carrega e exibe os detalhes do produto', async () => {
    api.get.mockResolvedValue({
      data: {
        data: {
          id: 1,
          nome: 'Pastel de Carne',
          preco: 12.5,
          foto: 'pastel.jpg'
        }
      }
    });

    router.push('/dashboard/produtos/1');
    await router.isReady();

    render(Mostrar, {
      global: { plugins: [router] }
    });

    await waitFor(() => {
      expect(api.get).toHaveBeenCalledWith('/api/produtos/1');
      expect(screen.getByText(/Pastel de Carne/i)).toBeInTheDocument();
      expect(screen.getByText(/12\.5/)).toBeInTheDocument();

      const img = screen.getByRole('img');
      expect(img.src).toContain('/storage/pastel.jpg');
    });
  });
});
