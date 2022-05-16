<section class="bg-white p-3 mb-3 rounded h-full">
    <h3 class="font-semibold mb-4 text-2xl">Audit Report</h3>

    @if($site->hasLighthouseReport())
        <iframe src="/lighthouse/{{$site->id}}/report.html" frameborder="0" class="w-full h-full overflow-hidden" height="100%" width="100%" style="height: 90vh"></iframe>
    @else
        <p class="bg-yellow-400 rounded p-3 flex justify-between items-center">
            We are currently performing an audit on your website. Please come back later for the full report. <i class="fa-solid fa-circle-exclamation"></i>
        </p>
    @endif
</section>