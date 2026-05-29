<?php
/**
 * Footer Template
 */
?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><?php echo APP_NAME; ?></h5>
                    <p>Profesyonel gayrimenkul yönetim sistemi</p>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Hızlı Linkler</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>listings" class="text-light text-decoration-none">İlanlar</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about" class="text-light text-decoration-none">Hakkında</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact" class="text-light text-decoration-none">İletişim</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>İletişim</h5>
                    <p>
                        <i class="fas fa-envelope"></i> <?php echo MAIL_FROM; ?><br>
                        <i class="fas fa-phone"></i> <?php echo defined('APP_PHONE') ? APP_PHONE : '+90 XXX XXX XX XX'; ?>
                    </p>
                </div>
            </div>
            
            <hr class="bg-light">
            
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Tüm hakları saklıdır.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="<?php echo BASE_URL; ?>privacy" class="text-light text-decoration-none me-3">Gizlilik</a>
                    <a href="<?php echo BASE_URL; ?>terms" class="text-light text-decoration-none">Şartlar</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo PUBLIC_URL; ?>js/script.js"></script>
</body>
</html>
