<!-- resources/views/components/footer.blade.php -->
<footer class="bg-dark text-white mt-auto">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Company Info -->
            <div>
                <h3 class="text-xl font-bold mb-4 text-yellow-400">PokerGiants.de</h3>
                <p class="text-gray-300 mb-4">
                    Die Zukunft des Sachpreis-Pokers.<br>Fair, sicher und unterhaltsam.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <x-icon name="facebook-f" type="fab" class="text-xl" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <x-icon name="twitter" type="fab" class="text-xl" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <x-icon name="instagram" type="fab" class="text-xl" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <x-icon name="linkedin-in" type="fab" class="text-xl" />
                    </a>
                </div>
            </div>

            <!-- Rechtliches -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Rechtliches</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">AGB</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Datenschutz</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Impressum</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cookie-Richtlinie</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Verantwortungsvolles Spielen</a></li>
                </ul>
            </div>

            <!-- Internes -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Internes</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors">Über uns</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Karriere</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Presse</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Partner</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Support</a></li>
                </ul>
            </div>

            <!-- Social & Contact -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontakt</h4>
                <ul class="space-y-2">
                    <li class="text-gray-400">
                        <span class="block">E-Mail:</span>
                        <a href="mailto:info@pokergiants.de" class="hover:text-white transition-colors">info@pokergiants.de</a>
                    </li>
                    <li class="text-gray-400">
                        <span class="block">Support:</span>
                        <a href="mailto:support@pokergiants.de" class="hover:text-white transition-colors">support@pokergiants.de</a>
                    </li>
                    <li class="text-gray-400">
                        <span class="block">Telefon:</span>
                        <span>+49 (0) 123 456789</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 text-sm mb-4 md:mb-0">
                © {{ date('Y') }} PokerGiants.de. Alle Rechte vorbehalten.
            </div>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Sitemap</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">RSS</a>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400">Made with ❤️ in Germany</span>
            </div>
        </div>
    </div>
</footer>