<?php
// src/Service/LoginRateLimiter.php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginRateLimiter
{
    private SessionInterface $session;
    private string $attemptKey = 'login_attempts';
    private string $lockKey = 'login_lock_until';

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function isAllowed(): bool
    {
        $now = time();

        // Vérifie si l'utilisateur est verrouillé
        $lockedUntil = $this->session->get($this->lockKey);
        if ($lockedUntil && $now < $lockedUntil) {
            return false;
        }

        $attempts = $this->session->get($this->attemptKey, []);

        // Nettoie les tentatives de plus de 5 minutes
        $attempts = array_filter($attempts, fn($ts) => $now - $ts <= 300);

        // Vérifie l'intervalle avec la dernière tentative
        if (!empty($attempts) && $now - end($attempts) < 10) {
            return false; // Moins de 10 secondes depuis la dernière
        }

        // Enregistre la tentative actuelle
        $attempts[] = $now;

        // Si plus de 5 tentatives récentes, blocage 5 minutes
        if (count($attempts) >= 5) {
            $this->session->set($this->lockKey, $now + 300); // blocage 5 min
            $this->session->remove($this->attemptKey); // reset
            return false;
        }

        // Sinon, met à jour la session avec les tentatives valides
        $this->session->set($this->attemptKey, $attempts);

        return true;
    }

    public function getRemainingLockTime(): int
    {
        $lock = $this->session->get($this->lockKey);
        return $lock ? max(0, $lock - time()) : 0;
    }
}
