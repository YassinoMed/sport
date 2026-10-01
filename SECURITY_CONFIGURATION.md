# 🛡️ Configuration de Sécurité Avancée
**Projet:** Système de Gestion de Gym  
**Date:** 30 Novembre 2025

---

## 🔒 Outils de Sécurité Implémentés

### 1. **Headers de Sécurité HTTP** ✅
**Middleware:** `SecurityHeaders.php`  
**Localisation:** `app/Http/Middleware/SecurityHeaders.php`

**Protections actives:**
- ✅ **X-Frame-Options:** Protection contre le clickjacking
- ✅ **X-Content-Type-Options:** Protection contre le MIME-sniffing
- ✅ **X-XSS-Protection:** Protection XSS intégrée au navigateur
- ✅ **Content-Security-Policy:** Politique de sécurité du contenu stricte
- ✅ **Referrer-Policy:** Contrôle des informations de référence
- ✅ **Permissions-Policy:** Désactivation des fonctionnalités sensibles (géolocalisation, caméra, micro)

**Application:** Automatique sur toutes les requêtes (middleware global)

---

### 2. **Rate Limiting Anti-Brute Force** ✅
**Middleware:** `RateLimitLogin.php`  
**Localisation:** `app/Http/Middleware/RateLimitLogin.php`

**Configuration:**
- **Limite:** 5 tentatives par minute par IP
- **Délai de blocage:** 60 secondes
- **Routes protégées:** 
  - POST `/login`
  - POST `/register`

**Réponse en cas de dépassement:**
```json
{
  "message": "Trop de tentatives de connexion. Veuillez réessayer dans X secondes.",
  "retry_after": X
}
```

---

### 3. **Sanitization des Entrées** ✅
**Middleware:** `SanitizeInput.php`  
**Localisation:** `app/Http/Middleware/SanitizeInput.php`

**Nettoyage automatique:**
- Suppression des balises HTML dangereuses
- Trim des espaces en début et fin
- Protection contre les injections de scripts

---

### 4. **Protection Apache (.htaccess)** ✅
**Fichier:** `public/.htaccess`

**Protections actives:**
- ✅ Blocage de l'accès aux fichiers sensibles (.env, .git, .log, etc.)
- ✅ Désactivation de l'indexation des répertoires
- ✅ Headers de sécurité HTTP
- ✅ Protection contre les injections SQL via URL
- ✅ Limitation de la taille des requêtes (anti-DoS)
- ✅ Compression GZIP
- ✅ Cache des ressources statiques

---

## 📊 Matrice de Protection

| Type d'Attaque | Protection | Niveau | Statut |
|----------------|------------|--------|--------|
| **XSS (Cross-Site Scripting)** | Headers + Échappement Blade | 🟢 Élevé | ✅ Actif |
| **CSRF (Cross-Site Request Forgery)** | Token CSRF Laravel | 🟢 Élevé | ✅ Actif |
| **SQL Injection** | ORM Eloquent + Validation | 🟢 Élevé | ✅ Actif |
| **Clickjacking** | X-Frame-Options | 🟢 Élevé | ✅ Actif |
| **MIME Sniffing** | X-Content-Type-Options | 🟢 Élevé | ✅ Actif |
| **Brute Force** | Rate Limiting | 🟢 Élevé | ✅ Actif |
| **Session Hijacking** | Cookies sécurisés + HTTPS | 🟡 Moyen | ⚠️ Partiel |
| **DoS (Denial of Service)** | Rate Limiting + Timeout | 🟡 Moyen | ✅ Actif |
| **Directory Traversal** | .htaccess + Laravel | 🟢 Élevé | ✅ Actif |
| **Information Disclosure** | Debug OFF + Headers | 🟢 Élevé | ✅ Actif |

---

## 🔧 Configuration Requise

### Variables d'Environnement (.env)
```env
APP_ENV=production
APP_DEBUG=false  # ✅ CRITIQUE - Doit être false en production
APP_KEY=base64:... # ✅ Doit être généré et unique
SESSION_SECURE_COOKIE=true  # Si HTTPS est activé
SESSION_SAME_SITE=strict
```

### Modules Apache Requis
```bash
# Vérifier que ces modules sont activés
a2enmod headers
a2enmod rewrite
a2enmod deflate
a2enmod expires
systemctl restart apache2
```

---

## 📝 Checklist de Déploiement Sécurisé

### Avant le Déploiement
- [ ] `APP_DEBUG=false` dans `.env`
- [ ] `APP_ENV=production` dans `.env`
- [ ] Clé APP_KEY générée et unique
- [ ] Fichiers sensibles dans `.gitignore`
- [ ] Permissions correctes sur les dossiers (755 pour dossiers, 644 pour fichiers)
- [ ] `storage/` et `bootstrap/cache/` accessibles en écriture

### Après le Déploiement
- [ ] Vérifier les headers de sécurité (utiliser https://securityheaders.com)
- [ ] Tester le rate limiting sur `/login`
- [ ] Vérifier que les fichiers `.env` ne sont pas accessibles
- [ ] Tester la protection CSRF
- [ ] Configurer les sauvegardes automatiques
- [ ] Mettre en place la rotation des logs

---

## 🚀 Commandes de Maintenance

### Nettoyer le cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Optimiser pour la production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Vérifier les permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Surveiller les logs
```bash
tail -f storage/logs/laravel.log
```

---

## 🔍 Monitoring et Alertes

### Logs à Surveiller
1. **Tentatives de connexion échouées** - Fichier: `storage/logs/laravel.log`
2. **Erreurs 403/404** - Logs Apache/Nginx
3. **Rate limiting dépassé** - Laravel logs
4. **Erreurs de validation** - Laravel logs

### Alertes Recommandées
- Plus de 10 tentatives de connexion échouées en 5 minutes
- Accès aux fichiers sensibles (.env, .git)
- Erreurs 500 répétées
- Utilisation disque > 80%

---

## 📞 En Cas d'Incident

### Procédure d'Urgence
1. **Activer le mode maintenance:**
   ```bash
   php artisan down --secret="token-secret-123"
   ```

2. **Analyser les logs:**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

3. **Bloquer une IP suspecte (.htaccess):**
   ```apache
   Deny from XXX.XXX.XXX.XXX
   ```

4. **Restaurer depuis une sauvegarde si nécessaire**

5. **Désactiver le mode maintenance:**
   ```bash
   php artisan up
   ```

---

## 📚 Ressources Supplémentaires

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/9.x/security)
- [Security Headers](https://securityheaders.com/)
- [Mozilla Observatory](https://observatory.mozilla.org/)

---

**Document maintenu par:** Équipe DevSecOps  
**Dernière révision:** 30 Novembre 2025
