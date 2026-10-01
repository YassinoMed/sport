#!/bin/bash

# Script de vérification de l'uniformisation des styles
# Usage: ./verify-styles.sh

echo "🔍 Vérification de l'uniformisation des styles"
echo "=============================================="
echo ""

# Couleurs pour l'output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "📋 Vérification des variables CSS..."

# Vérifier que custom-styles.css existe
if [ -f "public/css/custom-styles.css" ]; then
    echo -e "${GREEN}✓${NC} custom-styles.css existe"
else
    echo -e "${RED}✗${NC} custom-styles.css manquant!"
fi

# Vérifier que les variables CSS sont présentes
if grep -q "--primary-gradient" public/css/custom-styles.css; then
    echo -e "${GREEN}✓${NC} Variables CSS trouvées"
else
    echo -e "${RED}✗${NC} Variables CSS manquantes!"
fi

echo ""
echo "📄 Vérification des fichiers Blade..."

# Vérifier reports/index.blade.php
if [ -f "resources/views/reports/index.blade.php" ]; then
    echo -e "${GREEN}✓${NC} reports/index.blade.php existe"
    
    # Vérifier si le fichier utilise les variables CSS
    if grep -q "var(--primary-gradient)" resources/views/reports/index.blade.php; then
        echo -e "${GREEN}✓✓${NC} reports/index.blade.php utilise les variables CSS"
    else
        echo -e "${YELLOW}⚠${NC} reports/index.blade.php pourrait ne pas utiliser toutes les variables"
    fi
else
    echo -e "${RED}✗${NC} reports/index.blade.php manquant!"
fi

echo ""
echo "📚 Documentation..."

if [ -f "STYLE_GUIDE.md" ]; then
    echo -e "${GREEN}✓${NC} STYLE_GUIDE.md créé"
else
    echo -e "${YELLOW}⚠${NC} STYLE_GUIDE.md manquant"
fi

if [ -f "TRAVAIL_UNIFORMISATION.md" ]; then
    echo -e "${GREEN}✓${NC} TRAVAIL_UNIFORMISATION.md créé"
else
    echo -e "${YELLOW}⚠${NC} TRAVAIL_UNIFORMISATION.md manquant"
fi

echo ""
echo "🌐 URLs à tester:"
echo "  • Page de rapports: http://91.134.134.100/reports"
echo "  • Page d'accueil: http://91.134.134.100/"
echo "  • Liste des utilisateurs: http://91.134.134.100/allUsers/list"
echo ""

echo "✅ Vérification terminée!"
echo ""
echo "Pour démarrer le serveur Laravel (si nécessaire):"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo ""
