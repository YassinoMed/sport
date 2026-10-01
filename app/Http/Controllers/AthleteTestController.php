<?php

namespace App\Http\Controllers;

use App\Models\AthleteTest;
use App\Models\AthleteTestResult;
use App\Models\User;
use App\Models\Belt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AthleteTestController extends Controller
{
    public function index()
    {
        $tests = AthleteTest::orderBy('category')->orderBy('name_fr')->get();
        $belts = Belt::with('user')->orderByDesc('achieved_at')->limit(20)->get();
        return view('athlete_tests.index', compact('tests','belts'));
    }

    public function seedDefaults()
    {
        $catalog = [
            ['Vitesse - 10 m', 'تسارع 10 م', 'Physique', 's', 'Accélération pure sur 10 m'],
            ['Vitesse - 30 m', 'سرعة 30 م', 'Physique', 's', 'Vitesse maximale sur 30 m'],
            ['Vitesse - 40 m', 'سرعة 40 م', 'Physique', 's', 'Vitesse prolongée 40 m'],
            ['Endurance - VMA (Léger-Boucher)', 'اختبار VMA', 'Physique', 'km/h', 'VO2 max indirect'],
            ['Endurance - Cooper 12 min', 'كوبر 12 دقيقة', 'Physique', 'm', 'Distance en 12 min'],
            ['Endurance - Yo-Yo test', 'يو-يو انترميتنت', 'Physique', 'palier', 'Résistance au rythme élevé'],
            ['Force - 1RM Squat', '1RM سكوات', 'Physique', 'kg', 'Force inférieure'],
            ['Force - 1RM Bench Press', '1RM بنش', 'Physique', 'kg', 'Développé couché'],
            ['Force - Saut vertical (CMJ)', 'قفزة عمودية', 'Physique', 'cm', 'Counter Movement Jump'],
            ['Force - Saut horizontal', 'قفزة أفقية', 'Physique', 'cm', 'Saut en longueur statique'],
            ['Puissance - Sprint puissance', 'سباق القوة', 'Physique', 'W', 'Power sprint'],
            ['Puissance - Force-vitesse (F-v)', 'قوة-سرعة', 'Physique', 'profil', 'Profil F-v'],
            ['Puissance - Lancer medecine ball', 'رمي الكرة الطبية', 'Physique', 'm', 'Throw test'],
            ['Agilité - T test', 'اختبار T', 'Physique', 's', 'Agilité T'],
            ['Agilité - Illinois', 'اختبار إلينوي', 'Physique', 's', 'Illinois agility'],
            ['Agilité - Shuttle 5-10-5', 'شاتل 5-10-5', 'Physique', 's', 'Changements de direction'],
            ['Mobilité - Sit and Reach', 'جلوس والوصول', 'Physique', 'cm', 'Souplesse chaîne postérieure'],
            ['Mobilité - Articulations', 'حركية المفاصل', 'Physique', null, 'Hanches/chevilles/épaules'],
            ['Technique - Gestuelle spécifique', 'الحركات التقنية', 'Technique', null, 'Notation qualitative'],
            ['Technique - Précision', 'الدقة', 'Technique', '%', 'Précision passes/tirs'],
            ['Technique - Coordination', 'تنسيق العين-اليد/القدم', 'Technique', null, 'Coordination spécifique'],
            ['Technique - Répétition sous fatigue', 'تكرار تحت التعب', 'Technique', null, 'Qualitative'],
            ['Tactique - Lecture du jeu', 'قراءة اللعب', 'Tactique', null, 'Qualitative'],
            ['Tactique - Décision rapide', 'اتخاذ قرار سريع', 'Tactique', 'ms', 'Temps réaction'],
            ['Tactique - Réaction', 'رد الفعل', 'Tactique', 'ms', 'Reaction time'],
            ['Tactique - Scénarios', 'سيناريوهات', 'Tactique', null, 'Simulation'],
            ['Mental - Résistance au stress', 'مقاومة الضغط', 'Mental', null, 'Questionnaires'],
            ['Mental - Focus/Concentration', 'التركيز', 'Mental', null, 'Scores'],
            ['Mental - Gestion de pression', 'إدارة الضغط التنافسي', 'Mental', null, 'Scores'],
            ['Mental - Motivation/Résilience', 'التحفيز/المرونة', 'Mental', null, 'Scores'],
            ['Récupération - HRV', 'تقلب معدل نبضات القلب', 'Récupération', 'ms', 'HRV'],
            ['Récupération - Sommeil', 'جودة النوم', 'Récupération', null, 'Scores/hrs'],
            ['Récupération - Fatigue neuromusculaire', 'تعب عصبي عضلي', 'Récupération', 'cm', 'CMJ monitoring'],
            ['Santé - Composition corporelle', 'تكوين الجسم', 'Santé', '%', 'Masse grasse/muscle'],
            ['Santé - IMC', 'مؤشر كتلة الجسم', 'Santé', null, 'BMI'],
            ['Santé - Tension artérielle', 'ضغط الدم', 'Santé', 'mmHg', 'BP'],
            ['Santé - Lactate (seuils)', 'اللاكتات', 'Santé', 'mmol/L', 'Seuil aérobie/anaérobie'],
        ];

        $created = 0; $skipped = 0;
        foreach ($catalog as [$fr,$ar,$cat,$unit,$desc]) {
            $code = Str::slug($fr, '-');
            if (AthleteTest::where('code',$code)->exists()) { $skipped++; continue; }
            AthleteTest::create([
                'code' => $code,
                'name_fr' => $fr,
                'name_ar' => $ar,
                'category' => $cat,
                'unit' => $unit,
                'description' => $desc,
            ]);
            $created++;
        }
        return redirect()->route('athlete-tests.index')->with('success', __('messages.tests_added', ['created' => $created, 'skipped' => $skipped]));
    }

    public function seedReplace()
    {
        // Efface le catalogue et applique la liste fournie (FR/AR)
        AthleteTest::query()->delete();
        $catalog = [
            // Réaction
            ['Réaction', 'Ruler drop test', 'اختبار سقوط المسطرة', 'ms', 'Temps de réaction (règle)'],
            ['Réaction', 'Réaction lumineuse', 'اختبار اللمس الضوئي', 'ms', 'Temps de réaction lumière'],
            ['Réaction', 'Vitesse de l\'uppercut sur cible (10 s)', 'اختبار سرعة اللكم على الهدف (10 ثوان)', 'coups/10s', 'Nombre de coups en 10 s'],
            ['Réaction', 'Réaction balle tombante', 'اختبار التقاط الكرة الساقطة', '%', 'Réussites (%)'],
            // Flexibilité
            ['Flexibilité', 'Sit and Reach', 'اختبار الجلوس والوصول', 'cm', 'Souplesse chaîne postérieure'],
            ['Flexibilité', 'Flexion avant', 'المرونة الأمامية', 'qual', 'Qualitative'],
            ['Flexibilité', 'Flexion latérale', 'المرونة الجانبية', 'qual', 'Qualitative'],
            ['Flexibilité', 'Mobilité épaules', 'مرونة الكتف', 'qual', 'Qualitative'],
            // Force
            ['Force', 'Pompes (5 min)', 'اختبار الضغط', 'reps', 'Total sur durée'],
            ['Force', 'Bench Press 1RM', 'اختبار البنش', 'kg', 'Force maximale'],
            ['Force', 'Force de préhension', 'اختبار قوة القبضة', 'kg', 'Dynamomètre'],
            // Endurance
            ['Endurance', 'Cooper 12 min', 'اختبار الجري 12 دقيقة كوبر', 'm', 'Distance en 12 min'],
            ['Endurance', 'Beep test (20 m)', 'اختبار الجري المتدرج 20 متر (بيب تست)', 'palier', 'Palier atteint'],
            ['Endurance', 'Endurance combat 3 min', 'اختبار التحمل القتالي 3 دقائق', 'score', 'Score d\'activité'],
            // Agilité
            ['Agilité', 'T-test', 'اختبار الرشاقة على شكل T', 's', 'Changements de direction'],
            ['Agilité', 'Shuffle latéral rapide', 'اختبار الانتقال الجانبي السريع', 's', 'Déplacements latéraux'],
            ['Agilité', '5-10-5', 'اختبار 5-10-5', 's', 'Agilité 3 cônes'],
            // Équilibre
            ['Équilibre', 'Monopodal (s)', 'اختبار الوقوف على ساق واحدة', 's', 'Durée de maintien'],
            ['Équilibre', 'Planche d\'équilibre', 'اختبار لوحة التوازن', 'score', 'Score stabilité'],
            // Additionnels spécifiques
            ['Spécifique', 'Punching speed (10 s)', 'اختبار سرعة اللكم (10 ثوان)', 'coups/10s', 'Rythme de coups'],
            ['Spécifique', 'Kicking speed (10 s)', 'اختبار سرعة الركل (10 ثوان)', 'coups/10s', 'Rythme de coups'],
            ['Spécifique', 'Sprint 30 m', 'اختبار الجري السريع 30 متر', 's', 'Vitesse 30 m'],
            ['Spécifique', 'Saut en longueur statique', 'اختبار القفز الطويل من الثبات', 'cm', 'Distance'],
            ['Spécifique', 'Changement direction 6 cônes', 'اختبار تغيير الاتجاه حول 6 أقماع', 's', 'Agilité 6 cônes'],
            ['Spécifique', 'Pas du guerrier', 'اختبار خطوة المحارب', 'reps', 'Répétitions'],
            ['Spécifique', 'Stabilité posture du cheval', 'اختبار ثبات وضعية الحصان', 's', 'Durée'],
            ['Spécifique', 'Stabilité posture de l\'arc', 'اختبار ثبات وضعية القوس', 's', 'Durée'],
            ['Spécifique', 'Battle rope (tirage)', 'اختبار سحب الحبل القتالي', 'reps/30s', 'Répétitions 30 s'],
            ['Spécifique', 'Punch puissance', 'اختبار اللكم بالقوة', 'score', 'Indice puissance'],
            ['Spécifique', 'Burpees 5 min', 'اختبار Burpees لمدة 5 دقائق', 'reps', 'Total en 5 min'],
            ['Spécifique', 'Corde rapide 2 min', 'اختبار الحبل السريع 2 دقيقة', 'reps', 'Total en 2 min'],
            ['Spécifique', 'Précision punch', 'اختبار دقة اللكم', '%', 'Précision \'%\''],
            ['Spécifique', 'Précision kick', 'اختبار دقة الركل', '%', 'Précision \'%\''],
            ['Spécifique', 'Équilibre kick statique', 'اختبار توازن الركلة الثابتة', 's', 'Durée de maintien'],
        ];

        foreach ($catalog as [$cat,$fr,$ar,$unit,$desc]) {
            $code = \Illuminate\Support\Str::slug($fr,'-');
            AthleteTest::create([
                'code' => $code,
                'name_fr' => $fr,
                'name_ar' => $ar,
                'category' => $cat,
                'unit' => $unit,
                'description' => $desc,
            ]);
        }
        return redirect()->route('athlete-tests.index')->with('success', __('messages.catalog_replaced'));
    }

    public function createTest()
    {
        return view('athlete_tests.create_test');
    }

    public function storeTest(Request $request)
    {
        $data = $request->validate([
            'name_fr' => ['required','string','max:150'],
            'name_ar' => ['nullable','string','max:150'],
            'category' => ['required','string','max:50'],
            'unit' => ['nullable','string','max:20'],
            'description' => ['nullable','string','max:500'],
        ]);
        $code = \Illuminate\Support\Str::slug($data['name_fr'],'-');
        if (AthleteTest::where('code',$code)->exists()) {
            $code .= '-' . substr(md5(uniqid('', true)),0,6);
        }
        AthleteTest::create(array_merge($data, ['code' => $code]));
        return redirect()->route('athlete-tests.index')->with('success', __('messages.test_created'));
    }

    public function editTest(AthleteTest $test)
    {
        return view('athlete_tests.edit_test', compact('test'));
    }

    public function updateTest(Request $request, AthleteTest $test)
    {
        $data = $request->validate([
            'name_fr' => ['required','string','max:150'],
            'name_ar' => ['nullable','string','max:150'],
            'category' => ['required','string','max:50'],
            'unit' => ['nullable','string','max:20'],
            'description' => ['nullable','string','max:500'],
        ]);
        // Keep existing code; optionally regenerate if name changed
        $test->update($data);
        return redirect()->route('athlete-tests.index')->with('success', __('messages.test_updated'));
    }

    public function destroyTest(AthleteTest $test)
    {
        $test->delete();
        if (request()->expectsJson()) {
            return response()->json(['success'=>1]);
        }
        return redirect()->route('athlete-tests.index')->with('success', __('messages.test_deleted'));
    }

    public function userReport(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $query = AthleteTestResult::with('test')->where('user_id', $user->id);
        $category = $request->string('category')->toString();
        $testId = $request->integer('athlete_test_id');
        if ($category) {
            $query->whereHas('test', function($q) use ($category){ $q->where('category', $category); });
        }
        if ($testId) {
            $query->where('athlete_test_id', $testId);
        }
        $results = $query->orderByDesc('measured_at')->get();

        $tests = AthleteTest::orderBy('category')->orderBy('name_fr')->get();
        $latestByTest = AthleteTestResult::with('test')
            ->where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->get()
            ->groupBy('athlete_test_id')
            ->map(function($grp){ return $grp->first(); });

        $chartTestId = $testId ?: optional($latestByTest->first())->athlete_test_id;
        $progressChart = null;
        if ($chartTestId) {
            $chartResults = AthleteTestResult::with('test')
                ->where('user_id', $user->id)
                ->where('athlete_test_id', $chartTestId)
                ->orderBy('measured_at')
                ->limit(30)
                ->get();

            $labels = [];
            $values = [];
            foreach ($chartResults as $r) {
                $raw = trim((string) $r->value);
                $filtered = preg_replace('/[^0-9,.\-]/', '', $raw);
                $filtered = str_replace(',', '.', (string) $filtered);
                $num = is_numeric($filtered) ? (float) $filtered : null;
                if ($num === null) {
                    continue;
                }
                $labels[] = optional($r->measured_at)->format('d/m/Y') ?: '';
                $values[] = $num;
            }

            $test = $chartResults->first()?->test;
            $progressChart = [
                'test_id' => $chartTestId,
                'test_name' => $test?->name_fr,
                'unit' => $test?->unit,
                'labels' => $labels,
                'values' => $values,
            ];
        }

        return view('athlete_tests.user_report', [
            'user' => $user,
            'results' => $results,
            'tests' => $tests,
            'filters' => ['category' => $category, 'athlete_test_id' => $testId],
            'latestByTest' => $latestByTest,
            'progressChart' => $progressChart,
        ]);
    }

    public function createResult($userId)
    {
        $user = User::findOrFail($userId);
        $tests = AthleteTest::orderBy('category')->orderBy('name_fr')->get();
        return view('athlete_tests.create_result', compact('user','tests'));
    }

    public function storeResult(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $data = $request->validate([
            'athlete_test_id' => ['required','exists:athlete_tests,id'],
            'value' => ['required','string','max:100'],
            'measured_at' => ['required','date'],
            'notes' => ['nullable','string','max:500'],
        ]);
        AthleteTestResult::create([
            'user_id' => $user->id,
            'athlete_test_id' => $data['athlete_test_id'],
            'value' => $data['value'],
            'measured_at' => Carbon::parse($data['measured_at']),
            'notes' => $data['notes'] ?? null,
        ]);
        return redirect()->route('athlete-tests.user', $user->id)->with('success', __('messages.result_recorded'));
    }
}
