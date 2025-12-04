<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ROPA Record</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; margin: 20px; }
        h1 { color: #f97316; font-size: 28px; text-align: center; margin-bottom: 20px; }
        h2, h3 { color: #f97316; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #f3f4f6; padding-bottom: 3px; }
        p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; font-weight: bold; }
        ul { margin: 5px 0 10px 20px; }
        li { margin-bottom: 3px; }
        .section { margin-bottom: 25px; }
        .highlight { background-color: #fef3c7; padding: 5px 10px; border-radius: 4px; display: inline-block; }
        .inline-label { font-weight: bold; }
    </style>
</head>
<body>
    <h1>ROPA Record</h1>

    <div class="section">
        <p><span class="inline-label">Organisation:</span> {{ $ropa->organisation_name ?? $ropa->other_organisation_name ?? 'Unnamed' }}</p>
        <p><span class="inline-label">Department:</span> {{ $ropa->department ?? $ropa->other_department ?? 'N/A' }}</p>
        <p><span class="inline-label">Status:</span> <span class="highlight">{{ $ropa->status }}</span></p>
        <p><span class="inline-label">Created At:</span> {{ $ropa->created_at->format('d/m/Y H:i') }}</p>
    </div>

    @php
        function renderArray($arr) {
            if(is_array($arr) && count($arr)) {
                echo '<ul>';
                foreach($arr as $item) {
                    if(is_array($item)) {
                        echo '<li>' . htmlspecialchars(json_encode($item)) . '</li>';
                    } else {
                        echo '<li>' . htmlspecialchars($item) . '</li>';
                    }
                }
                echo '</ul>';
            }
        }
    @endphp

    <div class="section">
        @if(!empty($ropa->processes))
            <h2>Processes</h2>
            {!! renderArray($ropa->processes) !!}
        @endif

        @if(!empty($ropa->data_sources))
            <h2>Data Sources</h2>
            {!! renderArray($ropa->data_sources) !!}
        @endif

        @if(!empty($ropa->data_formats))
            <h2>Data Formats</h2>
            {!! renderArray($ropa->data_formats) !!}
        @endif

        @if(!empty($ropa->information_nature))
            <h2>Information Nature</h2>
            {!! renderArray($ropa->information_nature) !!}
        @endif

        @if(!empty($ropa->personal_data_categories))
            <h2>Personal Data Categories</h2>
            {!! renderArray($ropa->personal_data_categories) !!}
        @endif

        @if(!empty($ropa->retention_rationale))
            <h2>Retention Rationale</h2>
            {!! renderArray($ropa->retention_rationale) !!}
        @endif
    </div>

    <div class="section">
        <h2>Sharing Details</h2>
        <p><span class="inline-label">Information Shared:</span> {{ $ropa->information_shared ? 'Yes' : 'No' }}</p>
        <p><span class="inline-label">Sharing Local:</span> {{ $ropa->sharing_local ? 'Yes' : 'No' }}</p>
        <p><span class="inline-label">Sharing Transborder:</span> {{ $ropa->sharing_transborder ? 'Yes' : 'No' }}</p>
        @if(!empty($ropa->local_organizations))
            <p><span class="inline-label">Local Organizations:</span></p>
            {!! renderArray($ropa->local_organizations) !!}
        @endif
        @if(!empty($ropa->transborder_countries))
            <p><span class="inline-label">Transborder Countries:</span></p>
            {!! renderArray($ropa->transborder_countries) !!}
        @endif
        <p><span class="inline-label">Sharing Comment:</span> {{ $ropa->sharing_comment ?? 'N/A' }}</p>
    </div>

    <div class="section">
        <h2>Security Measures</h2>
        @if(!empty($ropa->access_measures))
            <h3>Access Measures</h3>
            {!! renderArray($ropa->access_measures) !!}
        @endif
        @if(!empty($ropa->technical_measures))
            <h3>Technical Measures</h3>
            {!! renderArray($ropa->technical_measures) !!}
        @endif
        @if(!empty($ropa->organisational_measures))
            <h3>Organisational Measures</h3>
            {!! renderArray($ropa->organisational_measures) !!}
        @endif
    </div>

    <div class="section">
        @if(!empty($ropa->lawful_basis))
            <h2>Lawful Basis</h2>
            {!! renderArray($ropa->lawful_basis) !!}
        @endif

        @if(!empty($ropa->risk_report))
            <h2>Risk Report</h2>
            {!! renderArray($ropa->risk_report) !!}
        @endif
    </div>

</body>
</html>
