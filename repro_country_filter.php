$request = new \Illuminate\Http\Request();
$request->merge(['country' => 1]); // Assuming country ID 1 exists

// Test the relationship specifically
try {
    $country = new \App\Models\Country();
    if (method_exists($country, 'authors')) {
        echo "Success: 'authors' method exists on Country model.\n";
    } else {
        echo "Error: 'authors' method missing on Country model.\n";
    }

    $query = \App\Models\Country::whereHas('authors', function ($q) {
        $q->has('books');
    });
    
    // Just check the SQL generation to ensure no errors
    $sql = $query->toSql();
    echo "Generated SQL for Country::whereHas('authors'): " . $sql . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
