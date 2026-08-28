<?php
/**
 * Admin Panel Footer Layout
 */
?>
        </main>

        <footer class="p-6 bg-white border-t border-slate-200 text-center text-xs text-slate-400">
            &copy; <?= date('Y') ?> <?= sanitize(get_settings()['store_name']) ?> - Panel Administrasi Native PHP
        </footer>

    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
