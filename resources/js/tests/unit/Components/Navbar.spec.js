import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/vue';
import Navbar from '@/Components/Navbar.vue';
import { createRouter, createWebHistory } from 'vue-router';

describe('Navbar.vue', () => {
  let router;

  beforeEach(() => {
    router = createRouter({
      history: createWebHistory(),
      routes: [
        { path: '/dashboard/clientes' },
        { path: '/dashboard/produtos' },
        { path: '/dashboard/pedidos' },
        { path: '/login' }
      ]
    });
  });

  it('renderiza os botões principais', () => {
    render(Navbar, { global: { plugins: [router] } });

    expect(screen.getByText('Clientes')).toBeTruthy();
    expect(screen.getByText('Produtos')).toBeTruthy();
    expect(screen.getByText('Pedidos')).toBeTruthy();
    expect(screen.getByText('Sair')).toBeTruthy();
  });

  it('botão Sair remove token e redireciona', async () => {
    render(Navbar, { global: { plugins: [router] } });

    localStorage.setItem('token', 'abc123');
    const sairBtn = screen.getByText('Sair');

    await fireEvent.click(sairBtn);

    expect(localStorage.getItem('token')).toBeNull();
    expect(router.currentRoute.value.path).toBe('/');
  });
});
