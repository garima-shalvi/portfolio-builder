<?php

session_start();

if (!isset($_SESSION['auth_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

require_once "db.php";
require_once __DIR__ . "/config.php";

header("Content-Type: application/json");

$user_id = $_SESSION['auth_id'];

$stmt = $conn->prepare(
    "SELECT id
     FROM portfolios
     WHERE user_id = ?
     ORDER BY id DESC
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$portfolio = $result->fetch_assoc();
$stmt->close();

if (!$portfolio) {
    http_response_code(404);
    echo json_encode(["error" => "Portfolio not found"]);
    exit();
}

$portfolio_id = $portfolio['id'];

$stmt = $conn->prepare(
    "SELECT name, about, linkedin, github, resume
     FROM portfolio_details
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$details = $stmt->get_result()->fetch_assoc();
$stmt->close();

$skills = [];

$stmt = $conn->prepare(
    "SELECT skill_name
     FROM skills
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
}

$stmt->close();

$education = [];

$stmt = $conn->prepare(
    "SELECT degree, institute, duration, score
     FROM education
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $education[] = $row;
}

$stmt->close();

$experience = [];

$stmt = $conn->prepare(
    "SELECT company, role, duration
     FROM experience
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $experience[] = $row;
}

$stmt->close();

$projects = [];

$stmt = $conn->prepare(
    "SELECT title, description, tech_stack, project_link
     FROM projects
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$stmt->close();

$certificates = [];

$stmt = $conn->prepare(
    "SELECT certificate_name
     FROM certificates
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $certificates[] = $row['certificate_name'];
}

$stmt->close();

$achievements = [];

$stmt = $conn->prepare(
    "SELECT title, description
     FROM achievements
     WHERE portfolio_id = ?"
);

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $achievements[] = $row;
}

$stmt->close();

$portfolio_data = [
    "name" => $details['name'] ?? "",
    "about" => $details['about'] ?? "",
    "skills" => $skills,
    "education" => $education,
    "experience" => $experience,
    "projects" => $projects,
    "certificates" => $certificates,
    "achievements" => $achievements,
    "github" => $details['github'] ?? "",
    "linkedin" => $details['linkedin'] ?? "",
    "has_resume" => !empty($details['resume'])
];

$force_refresh = isset($_GET['force']) && $_GET['force'] === '1';

if (!$force_refresh) {
    $stmt = $conn->prepare(
        "SELECT ai_review, updated_at
         FROM portfolio_analysis
         WHERE portfolio_id = ?"
    );

    $stmt->bind_param("i", $portfolio_id);
    $stmt->execute();

    $cached = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cached && !empty($cached['ai_review'])) {
        $cached_review = json_decode($cached['ai_review'], true);

        if (is_array($cached_review)) {
            echo json_encode([
                "review" => $cached_review,
                "cached" => true,
                "generated_at" => $cached['updated_at']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }
}

$portfolio_json = json_encode(
    $portfolio_data,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);

$prompt = <<<PROMPT
You are an AI career portfolio reviewer evaluating a portfolio for a software engineering internship or entry-level software development position.

Evaluate the actual content and quality of the portfolio. Do not score a category based merely on whether a field exists or how many items it contains.

Evaluate each category independently. A change in one category must not affect the score of another category.

Use only information present in the portfolio. Do not invent projects, skills, experience, achievements, technologies, responsibilities, or results.

The portfolio may contain vague, generic, duplicated, placeholder-like, meaningless, or low-information text. Identify such content and score it appropriately.

Scoring rubric:

Projects / 25:
0-5: Missing, placeholder, meaningless, or extremely weak projects.
6-10: Basic projects with limited explanation or technical evidence.
11-15: Reasonable projects demonstrating some technical ability.
16-20: Strong projects with clear implementation details and meaningful technical depth.
21-25: Highly substantial projects demonstrating strong engineering ability, technical depth, complexity, originality, or measurable outcomes.

Experience / 20:
0-4: Missing or meaningless experience.
5-8: Experience exists but provides little useful information.
9-12: Reasonably described experience with identifiable responsibilities.
13-16: Strong experience with meaningful technical responsibilities and contributions.
17-20: Highly relevant experience with substantial technical contributions, outcomes, or impact.

Skills / 15:
0-3: Missing, extremely limited, or unsupported skills.
4-6: Basic skills with little supporting evidence.
7-9: Reasonable range of relevant technical skills with some evidence.
10-12: Strong and relevant technical skill set supported by projects or experience.
13-15: Broad, well-supported and technically relevant skills demonstrating strong competence.

Profile / 10:
0-2: Missing, meaningless, placeholder-like, or extremely poor About section.
3-4: Generic profile with little useful information.
5-6: Clear background, interests, and career direction.
7-8: Specific, technically relevant, well-written and informative.
9-10: Highly compelling, specific and differentiated professional profile.

Professional Presence / 10:
0-2: Little or no professional presence.
3-4: Profiles or resume exist but accessibility or usefulness is weak.
5-6: GitHub, LinkedIn, resume, or portfolio links are present and reasonably accessible.
7-8: Strong professional presence with clear and useful links/resources.
9-10: Excellent professional presence with strong evidence of work and professional identity.
Evaluate the presence of links based only on the information provided. Do not claim that you visited or inspected external websites.

Education & Achievements / 20:
0-4: Missing or very weak.
5-8: Basic educational information with limited achievements.
9-12: Solid education and some meaningful achievements or certifications.
13-16: Strong academic background with relevant achievements, certifications, or competitive accomplishments.
17-20: Exceptional academic and achievement profile with highly relevant and verifiable accomplishments.

Important scoring rules:
- Score each category independently.
- Do not reward quantity alone.
- Do not penalize a category because another category is weak.
- Do not assume that a listed skill means the candidate is proficient in it.
- Look for evidence supporting claims.
- Meaningless text such as "hhhh", "asdf", or similar placeholder content should receive very low quality assessment.
- Duplicate or irrelevant information should reduce the relevant category's score.
- The overall score MUST equal the sum of the six category scores.
- All category scores must be integers.
- Overall score must be an integer from 0 to 100.
When evaluating weaknesses, distinguish between:
1. Missing information
2. Poorly explained existing information
3. Weak evidence of claimed skills
4. Placeholder or meaningless content
5. Incorrect or inaccessible information

Recommend fixing the highest-impact existing weaknesses first.

Recommendation rules:

- Every recommendation must directly address a specific weakness identified in the portfolio.
- Recommendations must be based only on information present in the portfolio.
- Do not recommend technologies, frameworks, certifications, projects, or achievements simply because they are popular, modern, or commonly requested by recruiters.
- Do not recommend adding another project when the existing projects can be strengthened through better descriptions, technical details, architecture, challenges, implementation details, or measurable outcomes.
- Do not recommend certifications unless the portfolio explicitly indicates that certifications are relevant to the candidate's goals or would meaningfully address a demonstrated weakness.
- Do not tell the candidate to add a skill unless there is evidence that the candidate already uses or is developing that skill.
- Prefer improving existing portfolio content over increasing the quantity of portfolio items.
- Recommendations should explain what the candidate should change and why that change would improve the identified weakness.
- Do not invent missing accomplishments, metrics, technologies, responsibilities, links, or qualifications.
- Avoid generic advice such as "learn more technologies", "add modern frameworks", "get certifications", or "build more projects" unless the portfolio evidence specifically justifies it.
- Recommendations must be specific enough that the candidate can directly act on them.
- If a technology is mentioned elsewhere in the portfolio but missing from the skills section, recommend adding it to the skills section only when the portfolio provides evidence that the candidate actually used or developed with it.
- Prefer moving or organizing already-supported skills into the skills section rather than inventing or adding new skills.

Return ONLY valid JSON. Do not use markdown or code fences.

Use exactly this structure:

{
  "overall_score": 0,
  "category_scores": {
    "projects": 0,
    "experience": 0,
    "skills": 0,
    "profile": 0,
    "professional_presence": 0,
    "education_achievements": 0
  },
  "strengths": [],
  "areas_to_improve": [],
  "recommendations": []
}

Provide:
- 2 to 4 concise strengths
- 2 to 4 specific areas to improve
- 2 to 5 actionable recommendations

Portfolio data:

$portfolio_json
PROMPT;

$data = [
    "model" => "openai/gpt-oss-20b",
    "messages" => [
        [
            "role" => "user",
            "content" => $prompt
        ]
    ],
    "temperature" => 0.1,
    "response_format" => [
        "type" => "json_object"
    ]
];

$max_retries = 3;
$review = null;
$last_error = null;

for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $groq_api_key
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);

    curl_close($ch);

    if ($response === false) {
        $last_error = $curl_error;
        break;
    }

    if ($http_code === 200) {
        $groq_response = json_decode($response, true);

        $content = $groq_response['choices'][0]['message']['content'] ?? null;

        if ($content) {
            $review = json_decode($content, true);

            if (is_array($review) &&
                isset($review['overall_score']) &&
                isset($review['category_scores']) &&
                isset($review['strengths']) &&
                isset($review['areas_to_improve']) &&
                isset($review['recommendations'])) {
                break;
            }
        }

        $last_error = "Invalid AI response";
        break;
    }

    if ($http_code === 429 || $http_code === 503) {
        $last_error = $response;

        if ($attempt < $max_retries) {
            sleep($attempt * 2);
            continue;
        }
    }

    $last_error = $response;
    break;
}

if (!$review) {
    http_response_code(500);
    echo json_encode([
        "error" => "AI review unavailable",
        "details" => $last_error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();
}

$upsert = $conn->prepare(
    "INSERT INTO portfolio_analysis (portfolio_id, ai_review)
     VALUES (?, ?)
     ON DUPLICATE KEY UPDATE
     ai_review = VALUES(ai_review),
     updated_at = CURRENT_TIMESTAMP"
);

$review_json = json_encode(
    $review,
    JSON_UNESCAPED_SLASHES
);

$upsert->bind_param(
    "is",
    $portfolio_id,
    $review_json
);

$upsert->execute();
$upsert->close();

echo json_encode([
    "review" => $review,
    "cached" => false,
    "generated_at" => date("Y-m-d H:i:s")
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>