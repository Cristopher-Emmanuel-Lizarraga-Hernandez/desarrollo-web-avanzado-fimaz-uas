<?php
// Admin class inherits everything from Usuario
class Admin extends Usuario {
    
    // We override getRol method to say it's Admin
    public function getRol() {
        return "Administrator";
    }
}
?>