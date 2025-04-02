<?php
session_start();

if (!isset($_SESSION['playerScore'])) {
    $_SESSION['playerScore'] = 0;
    $_SESSION['computerScore'] = 0;
    $_SESSION['roundHistory'] = [];
}

$humanChoice = null;
$computerChoice = null;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['play']) && isset($_POST['choices'])) {
        $humanChoice = (int)$_POST['choices'];
        $computerChoice = rand(1, 3);
        
        // Determine winner
        $winConditions = [
            1 => 3, // Rock beats scissors
            2 => 1, // Paper beats rock
            3 => 2  // Scissors beat paper
        ];
        
        if ($winConditions[$humanChoice] == $computerChoice) {
            $_SESSION['playerScore']++;
            $result = 'player';
        } elseif ($humanChoice == $computerChoice) {
            $result = 'tie';
        } else {
            $_SESSION['computerScore']++;
            $result = 'computer';
        }
        
        array_unshift($_SESSION['roundHistory'], [
            'player' => $humanChoice,
            'computer' => $computerChoice,
            'result' => $result,
            'timestamp' => date('H:i:s')
        ]);
        
        $_SESSION['roundHistory'] = array_slice($_SESSION['roundHistory'], 0, 5);
    }
    
    if (isset($_POST['reset'])) {
        $_SESSION['playerScore'] = 0;
        $_SESSION['computerScore'] = 0;
        $_SESSION['roundHistory'] = [];
    }
    
    if (isset($_POST['quit'])) {
        session_destroy();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$choices = [
    1 => ['name' => 'Rock', 'icon' => '✊', 'color' => 'text-blue-600'],
    2 => ['name' => 'Paper', 'icon' => '✋', 'color' => 'text-green-600'],
    3 => ['name' => 'Scissors', 'icon' => '✌️', 'color' => 'text-red-600']
];

$resultStyles = [
    'player' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'message' => 'You Win!'],
    'computer' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'message' => 'Computer Wins!'],
    'tie' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'message' => 'Tie Game!']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .choice-card {
            transition: all 0.2s ease;
            transform: scale(1);
        }
        .choice-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .choice-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .result-badge {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }
        .show-result .result-badge {
            opacity: 1;
            transform: translateY(0);
        }
        .hand-animation {
            animation: handPop 0.5s ease;
        }
        @keyframes handPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <header class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center text-gray-800">Rock Paper Scissors</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Score Board -->
            <div class="flex justify-between items-center mb-12 bg-white rounded-xl shadow-sm p-6">
                <div class="text-center">
                    <div class="text-2xl font-semibold text-gray-600">Player</div>
                    <div class="text-5xl font-bold text-blue-600"><?= $_SESSION['playerScore'] ?></div>
                </div>
                <div class="text-3xl font-bold text-gray-400">VS</div>
                <div class="text-center">
                    <div class="text-2xl font-semibold text-gray-600">Computer</div>
                    <div class="text-5xl font-bold text-red-600"><?= $_SESSION['computerScore'] ?></div>
                </div>
            </div>

            <!-- Game Area -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 <?= $result ? 'show-result' : '' ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                    <!-- Player Choice -->
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Your Choice</h3>
                        <div class="flex justify-center">
                            <div class="text-9xl hand-animation <?= $humanChoice ? $choices[$humanChoice]['color'] : 'text-gray-300' ?>">
                                <?= $humanChoice ? $choices[$humanChoice]['icon'] : '❔' ?>
                            </div>
                        </div>
                        <div class="result-badge mt-4 inline-block px-4 py-2 rounded-full <?= $result ? $resultStyles[$result]['bg'].' '.$resultStyles[$result]['text'] : '' ?>">
                            <?= $result ? $resultStyles[$result]['message'] : 'Make your choice' ?>
                        </div>
                    </div>

                    <!-- Computer Choice -->
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Computer</h3>
                        <div class="flex justify-center">
                            <div class="text-9xl hand-animation <?= $computerChoice ? $choices[$computerChoice]['color'] : 'text-gray-300' ?>">
                                <?= $computerChoice ? $choices[$computerChoice]['icon'] : '❔' ?>
                            </div>
                        </div>
                        <div class="result-badge mt-4 inline-block px-4 py-2 rounded-full <?= $result ? ($result === 'player' ? $resultStyles['computer']['bg'].' '.$resultStyles['computer']['text'] : ($result === 'computer' ? $resultStyles['player']['bg'].' '.$resultStyles['player']['text'] : $resultStyles['tie']['bg'].' '.$resultStyles['tie']['text'])) : '' ?>">
                            <?= $result ? ($result === 'player' ? 'Computer Loses' : ($result === 'computer' ? 'Computer Wins!' : 'Tie Game!')) : 'Waiting...' ?>
                        </div>
                    </div>
                </div>

                <!-- Choice Selection -->
                <form method="post" class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <?php foreach ($choices as $value => $choice): ?>
                            <label class="choice-card cursor-pointer border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 <?= ($humanChoice == $value) ? 'selected' : '' ?>">
                                <input type="radio" name="choices" value="<?= $value ?>" class="hidden" <?= ($humanChoice == $value) ? 'checked' : '' ?> required>
                                <div class="text-5xl mb-2 <?= $choice['color'] ?>"><?= $choice['icon'] ?></div>
                                <div class="text-lg font-medium text-gray-700"><?= $choice['name'] ?></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        <button type="submit" name="play" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Play Round
                        </button>
                        <button type="submit" name="reset" class="px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            Reset Scores
                        </button>
                        <button type="submit" name="quit" class="px-6 py-3 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition">
                            Quit Game
                        </button>
                    </div>
                </form>
            </div>

            <!-- Game History -->
            <?php if (!empty($_SESSION['roundHistory'])): ?>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Games</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($_SESSION['roundHistory'] as $index => $round): ?>
                    <div class="px-6 py-4 grid grid-cols-5 gap-4 items-center">
                        <div class="text-sm font-medium text-gray-500">Round <?= $index + 1 ?></div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-2 <?= $choices[$round['player']]['color'] ?>"><?= $choices[$round['player']]['icon'] ?></span>
                            <span><?= $choices[$round['player']]['name'] ?></span>
                        </div>
                        <div class="text-center text-gray-400">vs</div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-2 <?= $choices[$round['computer']]['color'] ?>"><?= $choices[$round['computer']]['icon'] ?></span>
                            <span><?= $choices[$round['computer']]['name'] ?></span>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $resultStyles[$round['result']]['bg'] ?> <?= $resultStyles[$round['result']]['text'] ?>">
                                <?= $resultStyles[$round['result']]['message'] ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white py-4 border-t border-gray-200">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            Rock Paper Scissors Game &copy; <?= date('Y') ?>
        </div>
    </footer>

    <script>

        document.querySelectorAll('.choice-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.choice-card').forEach(c => {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });

        if (document.querySelector('.show-result')) {
            setTimeout(() => {
                document.querySelectorAll('.hand-animation').forEach(hand => {
                    hand.classList.add('animate-pulse');
                });
            }, 100);
        }
    </script>
</body>
</html>
