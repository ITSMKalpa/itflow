<ul class="nav nav-pills nav-fill mb-3">
  
  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-general.php") { echo "active"; } ?>" 
      href="settings-general.php">
      <i class="fa fa-2x fa-cog"></i><br>
      General
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-company.php") { echo "active"; } ?>" 
      href="settings-company.php">
      <i class="fa fa-fw fa-2x fa-building"></i><br>
      Company
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-mail.php") { echo "active"; } ?>" 
      href="settings-mail.php">
      <i class="fa fa-fw fa-2x fa-envelope"></i><br>
      Mail
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-carddav.php") { echo "active"; } ?>" 
      href="settings-carddav.php">
      <i class="fa fa-fw fa-2x fa-address-book"></i><br>
      CardDAV
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-defaults.php") { echo "active"; } ?>" 
      href="settings-defaults.php">
      <i class="fa fa-fw fa-2x fa-cog"></i><br>
      Defaults
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-invoice.php") { echo "active"; } ?>" 
      href="settings-invoice.php">
      <i class="fa fa-fw fa-2x fa-file"></i><br>
      Invoice
    </a> 
  </li>

  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-quote.php") { echo "active"; } ?>" 
      href="settings-quote.php">
      <i class="fa fa-fw fa-2x fa-file"></i><br>
      Quote
    </a> 
  </li>
 
  <li class="nav-item">
    <a class="nav-link <?php if(basename($_SERVER["REQUEST_URI"]) == "settings-backup.php") { echo "active"; } ?>" 
      href="settings-backup.php">
      <i class="fa fa-fw fa-2x fa-database"></i><br>
      Backup
    </a> 
  </li>

</ul>