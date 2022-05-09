<div>
    {{-- <tab-selector :tabs="{{json_encode([
         'General' => route("settings.general", ['site' => $site->id]),
         'Uptime' => route("settings.uptime", ['site' => $site->id]),
         'Certificate Health' => route("settings.ssl", ['site' => $site->id]),
         'Broken Links' => route("settings.links", ['site' => $site->id]),
         'Remove' => route("sites.remove", ['site' => $site->id])
     ])}}"></tab-selector> --}}
     <div class="hidden sm:block">
         <div class="border-b border-gray-200">
             <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                 <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
                 <a href="{{route("settings.index", ['site' => $site->id])}}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active
                         {{\Request::is('*/settings') ? "border-indigo-500 text-indigo-600":'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}}"
                    aria-current="page">
                    <i class="fas fa-cogs"></i> General
                 </a>
                 <a href="{{route("settings.uptime", ['site' => $site->id])}}"
                     class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active
                         {{\Request::is('*/uptime') ? "border-indigo-500 text-indigo-600":'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}}"
                    aria-current="page">
                    <i class="fas fa-globe-europe"></i>  Uptime
                 </a>
                  <a href="{{route("settings.ssl-certificate", ['site' => $site->id])}}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active
                         {{\Request::is('*/settings/ssl') ? "border-indigo-500 text-indigo-600":'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}}"
                    aria-current="page">
                    <i class="fas fa-stethoscope"></i> Certificate Health
                 </a>
                 {{-- <a href="{{route("settings.links", ['site' => $site->id])}}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active cursor-not-allowed
                         {{\Request::is('*/broken-links') ? "border-indigo-500 text-indigo-600":'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}}"
                    aria-current="page">
                     Broken links
                 </a> --}}
                 <a href="{{route("sites.delete", ['site' => $site->id])}}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active
                         {{\Request::is('*/delete') ? "border-indigo-500 text-indigo-600":'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}}"
                    aria-current="page">
                    <i class="fas fa-trash"></i> Remove
                 </a>
             </nav>
         </div>
     </div>
 </div>