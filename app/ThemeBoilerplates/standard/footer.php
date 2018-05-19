            </main>

            <?php get_template_part('partials/footer'); ?>
        </div>

        <?php if (WP_ENV === 'production') : ?>
            <?php get_template_part('partial/analytics'); ?>
        <?php endif; ?>

        <?php wp_footer(); ?>
    </body>
</html>