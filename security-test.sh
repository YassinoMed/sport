#!/bin/bash

# 🛡️ Script de Test de Sécurité Anti-SQLMap
# Ce script teste les protections contre les outils d'attaque

echo "🔒 Test de Sécurité - Protection Anti-SQLMap et Outils d'Attaque"
echo "=================================================================="
echo ""

TARGET="http://91.134.134.100"

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: User-Agent SQLMap
echo "Test 1: Détection User-Agent SQLMap"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -A "sqlmap/1.0" "$TARGET/login")
if [ "$RESPONSE" == "403" ]; then
    echo -e "${GREEN}✅ PASS${NC} - SQLMap User-Agent bloqué (403)"
else
    echo -e "${RED}❌ FAIL${NC} - SQLMap User-Agent non bloqué (Code: $RESPONSE)"
fi
echo ""

# Test 2: User-Agent Nikto
echo "Test 2: Détection User-Agent Nikto"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -A "Nikto/2.1.5" "$TARGET/login")
if [ "$RESPONSE" == "403" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Nikto User-Agent bloqué (403)"
else
    echo -e "${RED}❌ FAIL${NC} - Nikto User-Agent non bloqué (Code: $RESPONSE)"
fi
echo ""

# Test 3: SQL Injection dans URL
echo "Test 3: SQL Injection via URL (UNION SELECT)"
# Utilisation de %20 pour les espaces et encodage correct
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/login?id=1'%20UNION%20SELECT%20*%20FROM%20users--")
if [ "$RESPONSE" == "403" ]; then
    echo -e "${GREEN}✅ PASS${NC} - SQL Injection bloquée (403)"
else
    echo -e "${RED}❌ FAIL${NC} - SQL Injection non bloquée (Code: $RESPONSE)"
fi
echo ""

# Test 9: Honeypot Anti-Bot
echo "Test 9: Honeypot Anti-Bot"
# Envoi d'une requête POST avec le champ piège rempli, un Referer valide et un User-Agent de navigateur
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -X POST -e "http://91.134.134.100/login" -A "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36" -d "email=bot@test.com&password=123456&website_url=http://spam.com" "$TARGET/login")
if [ "$RESPONSE" == "422" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Bot détecté par Honeypot (422)"
else
    echo -e "${RED}❌ FAIL${NC} - Bot non détecté (Code: $RESPONSE)"
fi
echo ""

# Test 4: XSS dans URL
echo "Test 4: XSS via URL"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/search?q=<script>alert('xss')</script>")
if [ "$RESPONSE" == "403" ]; then
    echo -e "${GREEN}✅ PASS${NC} - XSS bloqué (403)"
else
    echo -e "${RED}❌ FAIL${NC} - XSS non bloqué (Code: $RESPONSE)"
fi
echo ""

# Test 5: Path Traversal
echo "Test 5: Path Traversal"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/../../etc/passwd")
if [ "$RESPONSE" == "403" ] || [ "$RESPONSE" == "404" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Path Traversal bloqué (Code: $RESPONSE)"
else
    echo -e "${RED}❌ FAIL${NC} - Path Traversal non bloqué (Code: $RESPONSE)"
fi
echo ""

# Test 6: Rate Limiting
echo "Test 6: Rate Limiting (10 requêtes rapides)"
BLOCKED=0
for i in {1..10}; do
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/login")
    if [ "$RESPONSE" == "429" ]; then
        BLOCKED=1
        break
    fi
done

if [ "$BLOCKED" == "1" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Rate Limiting actif (429)"
else
    echo -e "${YELLOW}⚠️  WARNING${NC} - Rate Limiting non déclenché (peut nécessiter plus de requêtes)"
fi
echo ""

# Test 7: Vérification des Headers de Sécurité
echo "Test 7: Headers de Sécurité"
HEADERS=$(curl -s -I "$TARGET" | grep -E "(X-Frame-Options|X-Content-Type-Options|X-XSS-Protection)")
if [ ! -z "$HEADERS" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Headers de sécurité présents:"
    echo "$HEADERS" | sed 's/^/  /'
else
    echo -e "${RED}❌ FAIL${NC} - Headers de sécurité manquants"
fi
echo ""

# Test 8: Accès aux fichiers sensibles
echo "Test 8: Protection des fichiers sensibles"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$TARGET/.env")
if [ "$RESPONSE" == "403" ] || [ "$RESPONSE" == "404" ]; then
    echo -e "${GREEN}✅ PASS${NC} - Fichier .env protégé (Code: $RESPONSE)"
else
    echo -e "${RED}❌ FAIL${NC} - Fichier .env accessible (Code: $RESPONSE)"
fi
echo ""

# Résumé
echo "=================================================================="
echo "🏁 Tests terminés"
echo ""
echo "💡 Recommandations:"
echo "  - Vérifier les logs: storage/logs/laravel.log"
echo "  - Surveiller les IPs bloquées dans le cache Laravel"
echo "  - Tester avec des outils réels (SQLMap, Nikto) en environnement de test"
echo ""
