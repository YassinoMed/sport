# Erreur 500 sur les pages Belt - Solution

## Problème identifié
L'erreur 500 est causée par un problème de permissions sur le dossier de sessions Laravel.

**Erreur dans les logs:**
```
file_put_contents(/home/ubuntu/mouradbenyahya/MouradBenYahya/storage/framework/sessions/...): Failed to open stream: Permission denied
```

## Solution

Exécutez les commandes suivantes en tant que sudo/root:

```bash
# 1. Corriger les permissions du dossier sessions
sudo chmod -R 775 storage/framework/sessions
sudo chown -R www-data:www-data storage/framework/sessions

# 2. Corriger tout le dossier storage (recommandé)
sudo chmod -R 775 storage
sudo chown -R www-data:www-data storage

# 3. Corriger bootstrap/cache
sudo chmod -R 775 bootstrap/cache
sudo chown -R www-data:www-data bootstrap/cache

# 4. Nettoyerles caches Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Vérification

Après avoir exécuté ces commandes, les URLs suivantes devraient fonctionner:
- http://91.134.134.100/belts/create
- http://91.134.134.100/belts/grading/create

## Note

Si vous utilisez un serveur web (Nginx/Apache), assurez-vous que:
1. L'utilisateur www-data a les droits sur le dossier storage
2. Le groupe www-data est  bien défini
3. Les permissions 775 permettent la lecture/écriture pour l'utilisateur et le groupe

---

**Date**: 25 décembre 2024
