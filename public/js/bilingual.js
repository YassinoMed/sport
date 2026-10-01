document.addEventListener('DOMContentLoaded', function () {
  var map = {
    'Accueil': 'Accueil / الرئيسية',
    'Profil': 'Profil / الملف الشخصي',
    'Se déconnecter': 'Se déconnecter / تسجيل الخروج',
    'Revenus': 'Revenus / الإيرادات',
    'Rapports': 'Rapports / التقارير',
    'Villes': 'Villes / المدن',
    'Toutes les villes': 'Toutes les villes / جميع المدن',
    'Ajouter': 'Ajouter / إضافة',
    'Restaurer supprimées': 'Restaurer supprimées / استعادة المحذوفات',
    'Gestionnaires de ville': 'Gestionnaires de ville / مدراء المدن',
    'Tous les gestionnaires de ville': 'Tous les gestionnaires de ville / جميع مدراء المدن',
    'Salles': 'Salles / القاعات',
    'Liste des salles': 'Liste des salles / قائمة القاعات',
    'Gestionnaires de salle': 'Gestionnaires de salle / مدراء القاعة',
    'Tous les gestionnaires': 'Tous les gestionnaires / جميع المدراء',
    'Coachs': 'Coachs / المدربون',
    'Tous les coachs': 'Tous les coachs / جميع المدربين',
    'Utilisateurs': 'Utilisateurs / المستخدمون',
    'Tous les utilisateurs': 'Tous les utilisateurs / جميع المستخدمين',
    'Forfaits': 'Forfaits / الباقات',
    'Liste des forfaits': 'Liste des forfaits / قائمة الباقات',
    'Acheter un forfait': 'Acheter un forfait / شراء باقة',
    'Achats': 'Achats / المشتريات',
    'Séances': 'Séances / الحصص',
    'Toutes les séances': 'Toutes les séances / جميع الحصص',
    'Cours': 'Cours / الدورات',
    'Liste des cours': 'Liste des cours / قائمة الدورات',
    'Ceintures': 'Ceintures / الأحزمة',
    'Toutes les ceintures': 'Toutes les ceintures / جميع الأحزمة',
    'Participation': 'Participation / المشاركة',
    'Liste des Participations': 'Liste des Participations / قائمة المشاركات',
    'Événements': 'Événements / الفعاليات',
    'Tous les événements': 'Tous les événements / جميع الفعاليات',
    'Créer un événement': 'Créer un événement / إنشاء فعالية',
    'Présence': 'Présence / الحضور',
    'Historique Sessions': 'Historique Sessions / سجل الحصص',
    'Présence Cours': 'Présence Cours / حضور الدورات',
    'Utilisateurs Bannis': 'Utilisateurs Bannis / المستخدمون المحظورون',
    'Appliquer': 'Appliquer / تطبيق',
    'Réinitialiser': 'Réinitialiser / إعادة تعيين',
    'Ajouter / إضافة': 'Ajouter / إضافة'
  };

  var selectors = ['a','button','h1','h2','h3','h4','label','th','td','p','span'];
  selectors.forEach(function(sel){
    document.querySelectorAll(sel).forEach(function(el){
      // Only process if the element has no child elements (only text)
      // and its text matches a key in the map
      if (el.children.length === 0) {
        var t = (el.innerText || '').trim();
        if (t && map[t]) {
          el.innerText = map[t];
        }
      }
    });
  });
});

