<?php

// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Connect to database
$db = new PDO(
    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Certificate types with their fees and required documents
$certificateTypes = [
    [
        'name' => 'Delayed Birth Registration',
        'fee' => 20.00,
        'required_documents' => json_encode([
            'Voter ID (2 copies, original + attested)',
            'LC/VC Certification (2 copies, original)',
            'YMA/YLA/YCA/MTP Certification (2 copies, original)',
            'Church Certification (2 copies, original)',
            'Passport Size Photo (2 copies)',
            'DOB Proof (Educational/Baptism/Medical/Kohhran Certificate - 2 copies)'
        ])
    ],
    [
        'name' => 'Delayed Death Registration',
        'fee' => 20.00,
        'required_documents' => json_encode([
            'Voter ID (2 copies, original + attested)',
            'LC/VC Certification (2 copies, original)',
            'YMA/YLA/YCA/MTP Certification (2 copies, original)',
            'Passport Size Photo (2 copies)',
            'Deceased\'s Name & Photograph (2 copies)',
            'Police General Diary Entry (for unnatural deaths, 2 attested copies)'
        ])
    ],
    [
        'name' => 'Tax Exemption',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Tribal Certificate (Xerox Copy)'
        ])
    ],
    [
        'name' => 'Scheduled Tribe Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'YMA Certification (Original)',
            'Birth Certificate (or parent\'s Voter ID)',
            'Passport Size Photo (1)'
        ])
    ],
    [
        'name' => 'Scheduled Caste Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'YMA Certification (Original)',
            'Birth Certificate (or parent\'s Voter ID)',
            'Passport Size Photo (1)'
        ])
    ],
    [
        'name' => 'Residential Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Birth Certificate (or parent\'s Voter ID)',
            'Passport Size Photo (1)'
        ])
    ],
    [
        'name' => 'Temporary Residential Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'YMA Certification (Original)',
            'Passport Size Photo (1)'
        ])
    ],
    [
        'name' => 'Income Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Birth Certificate',
            'LPC (for Govt. employees)',
            'Affidavit (if income exceeds ₹10 Lakhs)'
        ])
    ],
    [
        'name' => 'No Income Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)'
        ])
    ],
    [
        'name' => 'Marriage Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy) (for both spouses)',
            'LC/VC Certification (Original)',
            'Witnesses (3 persons) with Voter ID copies'
        ])
    ],
    [
        'name' => 'Non-Marriage Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Church Certification (Original)'
        ])
    ],
    [
        'name' => 'Character Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)'
        ])
    ],
    [
        'name' => 'Religion Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'Church Certification (Original)'
        ])
    ],
    [
        'name' => 'Dependency Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Applicant\'s Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Family Ration Card (Xerox Copy)',
            'Passport/VISA (for foreigners)'
        ])
    ],
    [
        'name' => 'Hailing Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'YMA Certification (Original)'
        ])
    ],
    [
        'name' => 'Relationship Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Birth Certificate (Xerox Copy)'
        ])
    ],
    [
        'name' => 'Survival Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Death Certificate (Xerox Copy)',
            'Family Ration Card (Xerox Copy)'
        ])
    ],
    [
        'name' => 'Next of Kin Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Death Certificate (Xerox Copy)',
            'Birth Certificate (Xerox Copy)',
            'Family Ration Card (Xerox Copy)'
        ])
    ],
    [
        'name' => 'Other Backward Class (OBC) Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Home Department Approval',
            'Passport Size Photo (1)'
        ])
    ],
    [
        'name' => 'Religious & Linguistic Minority Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Church Certification (Original)'
        ])
    ],
    [
        'name' => 'Study Break Certificate',
        'fee' => 10.00,
        'required_documents' => json_encode([
            'Voter ID (Xerox copy)',
            'LC/VC Certification (Original)',
            'Previous Academic Mark Sheets (Xerox copy)'
        ])
    ]
];

// Check if certificate types already exist
$stmt = $db->query('SELECT COUNT(*) FROM certificate_types');
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo "Certificate types already exist. Updating existing types...\n";
} else {
    echo "No certificate types found. Adding new types...\n";
}

// Insert or update certificate types
foreach ($certificateTypes as $type) {
    // Check if this certificate type already exists
    $checkStmt = $db->prepare('SELECT id FROM certificate_types WHERE name = ?');
    $checkStmt->execute([$type['name']]);
    $existingType = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingType) {
        // Update existing certificate type
        $updateStmt = $db->prepare('UPDATE certificate_types SET fee = ?, required_documents = ?, status = "active" WHERE id = ?');
        $updateStmt->execute([$type['fee'], $type['required_documents'], $existingType['id']]);
        echo "Updated certificate type: {$type['name']}\n";
    } else {
        // Insert new certificate type
        $insertStmt = $db->prepare('INSERT INTO certificate_types (name, fee, required_documents, status) VALUES (?, ?, ?, "active")');
        $insertStmt->execute([$type['name'], $type['fee'], $type['required_documents']]);
        echo "Added certificate type: {$type['name']}\n";
    }
}

echo "\nAll certificate types have been added successfully!\n";