/* Shared mock data for the 3 dashboard maquettes.
 * Mirrors the real models of the app: RentalContract, Booking, Vehicle, Client,
 * Payment, Invoice, FinancialTransaction, VehicleCredit, VehicleOilChange,
 * VehicleInsurance, VehicleVignette, VehicleTechnicalCheck, Agent/User, Notification.
 * Statuses & labels are the ones defined in the models (French). Currency: MAD.
 * Fictional people & agency — no real brand. "Today" = 10/11/2026.
 */
(function () {
  const fmtMAD = (n) => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(n).replace(/ /g, ' ') + ' MAD';
  const fmtNum = (n) => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(n).replace(/ /g, ' ');
  const fmtK = (n) => (n >= 1000 ? (n / 1000).toFixed(n >= 100000 ? 0 : 1).replace('.', ',') + ' k' : String(n));

  // RentalContract.status labels (model getStatusLabel)
  const CONTRACT_STATUS = {
    draft:       { label: 'Brouillon',  tone: 'neutral' },
    pending:     { label: 'En attente', tone: 'warning' },
    accepted:    { label: 'Accepté',    tone: 'info' },
    in_progress: { label: 'En cours',   tone: 'primary' },
    completed:   { label: 'Terminé',    tone: 'success' },
    cancelled:   { label: 'Annulé',     tone: 'danger' },
  };
  // Vehicle.status enum
  const VEHICLE_STATUS = {
    available:   { label: 'Disponible',     tone: 'success' },
    rented:      { label: 'En location',    tone: 'primary' }, // derived: active contract
    maintenance: { label: 'Maintenance',    tone: 'warning' },
    unavailable: { label: 'Indisponible',   tone: 'neutral' },
  };
  // Booking.status enum
  const BOOKING_STATUS = {
    pending:   { label: 'En attente', tone: 'warning' },
    confirmed: { label: 'Confirmée',  tone: 'success' },
    cancelled: { label: 'Annulée',    tone: 'danger' },
    converted: { label: 'Convertie',  tone: 'info' },
  };
  // Payment.status / method
  const PAYMENT_STATUS = {
    paid:    { label: 'Payé',      tone: 'success' },
    partial: { label: 'Partiel',   tone: 'warning' },
    pending: { label: 'En attente',tone: 'neutral' },
    late:    { label: 'En retard', tone: 'danger' },
  };
  const PAYMENT_METHOD = { cash: 'Espèces', card: 'Carte bancaire', transfer: 'Virement', cheque: 'Chèque' };
  const SOURCE = { backoffice: 'Backoffice', website: 'Site web', mobile: 'Mobile' };

  const agency = { name: 'Agence Casablanca Centre', city: 'Casablanca', plan: 'Pro', agencies: 3 };
  const currentUser = { name: 'Salma Idrissi', initials: 'SI', role: 'Gérante d’agence', email: 's.idrissi@exemple.ma' };

  const vehicles = [
    { id: 1,  brand: 'Dacia',    model: 'Logan',       plate: '48213-A-1',  year: 2024, color: 'Gris', km: 38420,  rate: 250, deposit: 3000, status: 'rented',      fuel: 'Diesel',   gear: 'Manuelle',    nextOil: 40000,  insurance: '2027-02-14', vignette: '2026-12-31', ctrl: '2027-05-02' },
    { id: 2,  brand: 'Renault',  model: 'Clio 5',      plate: '51877-B-6',  year: 2025, color: 'Blanc', km: 12650, rate: 320, deposit: 4000, status: 'available',   fuel: 'Essence',  gear: 'Manuelle',    nextOil: 15000,  insurance: '2026-11-22', vignette: '2026-12-31', ctrl: '2028-03-10' },
    { id: 3,  brand: 'Peugeot',  model: '208',         plate: '30145-D-6',  year: 2024, color: 'Rouge', km: 27310, rate: 330, deposit: 4000, status: 'rented',      fuel: 'Essence',  gear: 'Automatique', nextOil: 30000,  insurance: '2027-01-09', vignette: '2026-12-31', ctrl: '2027-09-18' },
    { id: 4,  brand: 'Dacia',    model: 'Duster',      plate: '72904-H-1',  year: 2023, color: 'Bleu',  km: 64980, rate: 420, deposit: 5000, status: 'maintenance', fuel: 'Diesel',   gear: 'Manuelle',    nextOil: 65000,  insurance: '2027-03-30', vignette: '2026-12-31', ctrl: '2026-11-18' },
    { id: 5,  brand: 'Hyundai',  model: 'Accent',      plate: '19560-A-26', year: 2024, color: 'Noir',  km: 33105, rate: 300, deposit: 3500, status: 'rented',      fuel: 'Essence',  gear: 'Automatique', nextOil: 35000,  insurance: '2027-06-01', vignette: '2026-12-31', ctrl: '2027-11-21' },
    { id: 6,  brand: 'Volkswagen', model: 'Golf 8',    plate: '60431-B-1',  year: 2025, color: 'Gris',  km: 9870,  rate: 550, deposit: 7000, status: 'available',   fuel: 'Diesel',   gear: 'Automatique', nextOil: 15000,  insurance: '2027-04-12', vignette: '2026-12-31', ctrl: '2029-01-05' },
    { id: 7,  brand: 'Toyota',   model: 'Corolla',     plate: '27718-W-6',  year: 2023, color: 'Blanc', km: 58240, rate: 480, deposit: 6000, status: 'rented',      fuel: 'Hybride',  gear: 'Automatique', nextOil: 60000,  insurance: '2026-11-28', vignette: '2026-12-31', ctrl: '2027-02-27' },
    { id: 8,  brand: 'Kia',      model: 'Picanto',     plate: '84402-A-1',  year: 2024, color: 'Jaune', km: 21460, rate: 220, deposit: 2500, status: 'available',   fuel: 'Essence',  gear: 'Manuelle',    nextOil: 25000,  insurance: '2027-07-19', vignette: '2026-12-31', ctrl: '2027-10-08' },
    { id: 9,  brand: 'Dacia',    model: 'Sandero Stepway', plate: '45390-D-1', year: 2025, color: 'Orange', km: 14320, rate: 280, deposit: 3000, status: 'rented', fuel: 'Essence', gear: 'Manuelle', nextOil: 15000, insurance: '2027-08-03', vignette: '2026-12-31', ctrl: '2028-06-14' },
    { id: 10, brand: 'Mercedes', model: 'Classe A',    plate: '11923-B-6',  year: 2024, color: 'Noir',  km: 31780, rate: 850, deposit: 10000, status: 'rented',     fuel: 'Diesel',   gear: 'Automatique', nextOil: 35000,  insurance: '2027-01-25', vignette: '2026-12-31', ctrl: '2027-12-01' },
    { id: 11, brand: 'Renault',  model: 'Express Van', plate: '93357-H-6',  year: 2022, color: 'Blanc', km: 97410, rate: 380, deposit: 4500, status: 'unavailable', fuel: 'Diesel',   gear: 'Manuelle',    nextOil: 100000, insurance: '2027-02-02', vignette: '2026-12-31', ctrl: '2026-12-09' },
    { id: 12, brand: 'Peugeot',  model: '3008',        plate: '56081-A-1',  year: 2024, color: 'Gris',  km: 42260, rate: 650, deposit: 8000, status: 'available',   fuel: 'Diesel',   gear: 'Automatique', nextOil: 45000,  insurance: '2027-05-16', vignette: '2026-12-31', ctrl: '2027-08-23' },
  ];
  const vehicleName = (v) => `${v.brand} ${v.model}`;

  const clients = [
    { id: 1,  name: 'Youssef El Amrani',  phone: '+212 6 61 24 87 03', email: 'y.elamrani@mail.ma',  city: 'Casablanca', cin: 'BE482915', license: '12/458796', nationality: 'Marocaine',  contracts: 9, spent: 18450, rating: 4.8, status: 'active', since: '2023-04-12' },
    { id: 2,  name: 'Sara Benjelloun',    phone: '+212 6 70 15 33 92', email: 'sara.bj@mail.ma',     city: 'Rabat',      cin: 'A729104',  license: '07/226431', nationality: 'Marocaine',  contracts: 4, spent: 7920,  rating: 4.6, status: 'active', since: '2024-09-03' },
    { id: 3,  name: 'Hamza Tazi',         phone: '+212 6 62 48 10 57', email: 'hamza.tazi@mail.ma',  city: 'Casablanca', cin: 'BK118743', license: '19/307715', nationality: 'Marocaine',  contracts: 6, spent: 14200, rating: 4.9, status: 'active', since: '2022-11-28' },
    { id: 4,  name: 'Julien Morel',       phone: '+33 6 12 45 78 90',  email: 'j.morel@mail.fr',     city: 'Lyon',       cin: '—',        license: 'FR 15AB77821', nationality: 'Française', passport: '19FV40217', contracts: 2, spent: 6650, rating: 4.5, status: 'active', since: '2025-06-17' },
    { id: 5,  name: 'Imane Chraibi',      phone: '+212 6 55 90 21 64', email: 'imane.ch@mail.ma',    city: 'Mohammedia', cin: 'T204587',  license: '04/119820', nationality: 'Marocaine',  contracts: 3, spent: 5400,  rating: 4.2, status: 'active', since: '2025-01-08' },
    { id: 6,  name: 'Mehdi Alaoui',       phone: '+212 6 48 77 30 15', email: 'm.alaoui@mail.ma',    city: 'Casablanca', cin: 'BH530126', license: '11/650338', nationality: 'Marocaine',  contracts: 1, spent: 1960,  rating: 3.1, status: 'blacklisted', since: '2026-03-21' },
    { id: 7,  name: 'Nadia Berrada',      phone: '+212 6 60 03 84 29', email: 'n.berrada@mail.ma',   city: 'Marrakech',  cin: 'E391762',  license: '09/874120', nationality: 'Marocaine',  contracts: 5, spent: 11300, rating: 4.7, status: 'active', since: '2023-07-30' },
    { id: 8,  name: 'Karim Ouazzani',     phone: '+212 6 66 52 19 48', email: 'k.ouazzani@mail.ma',  city: 'Casablanca', cin: 'BJ774019', license: '15/221904', nationality: 'Marocaine',  contracts: 7, spent: 23100, rating: 4.9, status: 'active', since: '2022-05-14' },
    { id: 9,  name: 'Lina Haddad',        phone: '+971 50 482 1176',   email: 'lina.h@mail.ae',      city: 'Dubaï',      cin: '—',        license: 'AE 2210874', nationality: 'Émiratie', passport: 'P8820441', contracts: 1, spent: 8500, rating: 5.0, status: 'active', since: '2026-10-02' },
    { id: 10, name: 'Omar Fassi Fihri',   phone: '+212 6 77 41 05 83', email: 'omar.ff@mail.ma',     city: 'Fès',        cin: 'C658213',  license: '02/530967', nationality: 'Marocaine',  contracts: 2, spent: 3920,  rating: 4.4, status: 'active', since: '2025-12-11' },
  ];

  const agents = [
    { id: 1, name: 'Salma Idrissi',   email: 's.idrissi@exemple.ma',  role: 'Gérante',      status: 'active',  lastLogin: 'Aujourd’hui, 09:12', contracts: 41, revenue: 98400, agency: 'Casablanca Centre' },
    { id: 2, name: 'Anas Belkadi',    email: 'a.belkadi@exemple.ma',  role: 'Agent',        status: 'active',  lastLogin: 'Aujourd’hui, 08:47', contracts: 37, revenue: 81250, agency: 'Casablanca Centre' },
    { id: 3, name: 'Rim Lahlou',      email: 'r.lahlou@exemple.ma',   role: 'Agent',        status: 'active',  lastLogin: 'Hier, 18:30',         contracts: 29, revenue: 64900, agency: 'Casablanca Centre' },
    { id: 4, name: 'Soufiane Kettani',email: 's.kettani@exemple.ma',  role: 'Agent',        status: 'active',  lastLogin: 'Aujourd’hui, 10:05', contracts: 22, revenue: 47300, agency: 'Aéroport Mohammed V' },
    { id: 5, name: 'Ghita Mansouri',  email: 'g.mansouri@exemple.ma', role: 'Comptable',    status: 'active',  lastLogin: '08/11/2026',          contracts: 0,  revenue: 0,     agency: 'Siège' },
    { id: 6, name: 'Yassine Rami',    email: 'y.rami@exemple.ma',     role: 'Agent',        status: 'inactive',lastLogin: '12/09/2026',          contracts: 11, revenue: 19800, agency: 'Marrakech Gueliz' },
    { id: 7, name: 'Admin Plateforme',email: 'admin@exemple.ma',      role: 'Super Admin',  status: 'active',  lastLogin: 'Aujourd’hui, 07:55', contracts: 0,  revenue: 0,     agency: 'Toutes' },
  ];
  const ROLES = [
    { name: 'Super Admin', users: 1, perms: 142, desc: 'Accès complet, agences & abonnements' },
    { name: 'Gérant',      users: 2, perms: 118, desc: 'Gestion de l’agence, finance, agents' },
    { name: 'Agent',       users: 4, perms: 46,  desc: 'Contrats, réservations, clients, véhicules' },
    { name: 'Comptable',   users: 1, perms: 38,  desc: 'Factures, paiements, finance' },
  ];

  // contracts — contract_number format CTR-YYYY-####
  const C = (n, client, vehicle, start, end, days, rate, discount, paid, status, source, agent, pickup) => {
    const total = days * rate - discount;
    return { id: n, number: `CTR-2026-${String(n).padStart(4, '0')}`, client, vehicle, start, end, days, rate, discount, total, paid, deposit: vehicles[vehicle - 1].deposit, status, source, agent, pickup, accept: status === 'pending' ? 'pending' : (status === 'cancelled' ? 'rejected' : 'accepted') };
  };
  const contracts = [
    C(418, 1, 1,  '2026-11-08', '2026-11-15', 7, 250, 0,   1750, 'in_progress', 'backoffice', 2, 'Agence Casablanca Centre'),
    C(417, 3, 3,  '2026-11-07', '2026-11-12', 5, 330, 150, 1500, 'in_progress', 'website',    1, 'Aéroport Mohammed V'),
    C(416, 9, 10, '2026-11-06', '2026-11-16', 10, 850, 0,  8500, 'in_progress', 'mobile',     1, 'Aéroport Mohammed V'),
    C(415, 7, 7,  '2026-11-05', '2026-11-13', 8, 480, 240, 2000, 'in_progress', 'website',    3, 'Gare Casa-Voyageurs'),
    C(414, 2, 5,  '2026-11-04', '2026-11-11', 7, 300, 0,   2100, 'in_progress', 'backoffice', 2, 'Agence Casablanca Centre'),
    C(413, 8, 9,  '2026-11-03', '2026-11-17', 14, 280, 420, 3000, 'in_progress', 'backoffice', 3, 'Agence Casablanca Centre'),
    C(419, 4, 6,  '2026-11-12', '2026-11-19', 7, 550, 0,   0,    'pending',     'website',    4, 'Aéroport Mohammed V'),
    C(420, 10, 12,'2026-11-14', '2026-11-18', 4, 650, 0,   1000, 'accepted',    'mobile',     2, 'Agence Casablanca Centre'),
    C(421, 5, 8,  '2026-11-11', '2026-11-13', 2, 220, 0,   0,    'draft',       'backoffice', 3, 'Agence Casablanca Centre'),
    C(412, 1, 2,  '2026-10-28', '2026-11-02', 5, 320, 0,   1600, 'completed',   'backoffice', 2, 'Agence Casablanca Centre'),
    C(411, 3, 6,  '2026-10-25', '2026-10-31', 6, 550, 300, 3000, 'completed',   'website',    1, 'Aéroport Mohammed V'),
    C(410, 6, 4,  '2026-10-22', '2026-10-24', 2, 420, 0,   0,    'cancelled',   'website',    4, 'Agence Casablanca Centre'),
    C(409, 7, 12, '2026-10-19', '2026-10-26', 7, 650, 350, 4200, 'completed',   'mobile',     3, 'Gare Casa-Voyageurs'),
    C(408, 8, 10, '2026-10-15', '2026-10-22', 7, 850, 0,   5950, 'completed',   'backoffice', 1, 'Agence Casablanca Centre'),
    C(407, 2, 8,  '2026-10-12', '2026-10-15', 3, 220, 0,   660,  'completed',   'website',    2, 'Agence Casablanca Centre'),
    C(406, 5, 3,  '2026-10-09', '2026-10-14', 5, 330, 0,   1650, 'completed',   'backoffice', 4, 'Aéroport Mohammed V'),
  ];

  // Detailed record used by the "detail page" (contract 413: in progress, partial payment, 2nd driver)
  const contractDetail = {
    id: 413,
    secondaryClient: { name: 'Rania Ouazzani', license: '18/402277', phone: '+212 6 61 90 44 12' },
    startTime: '10:00', endTime: '18:00', dropoff: 'Agence Casablanca Centre',
    kmOut: 13940, fuelOut: '3/4', fuelPolicy: 'Même niveau',
    observations: 'Rayure légère pare-choc arrière (déjà présente). Siège bébé fourni.',
    payments: [
      { date: '03/11/2026', amount: 2000, method: 'card', ref: 'TPE-88231', status: 'paid' },
      { date: '07/11/2026', amount: 1000, method: 'cash', ref: 'CAISSE-0912', status: 'paid' },
    ],
    invoice: { number: 'FAC-2026-0388', ht: 2917, vat: 20, ttc: 3500 },
    control: [
      { item: 'Carrosserie', out: 'yes', note: 'Rayure arrière notée' },
      { item: 'Pneus & roue de secours', out: 'yes' },
      { item: 'Niveau d’huile', out: 'yes' },
      { item: 'Gilet & triangle', out: 'yes' },
      { item: 'Carte grise & assurance', out: 'yes' },
      { item: 'Propreté intérieure', out: 'na' },
    ],
    timeline: [
      { when: '03/11/2026 09:41', who: 'Rim Lahlou', what: 'Contrat créé', icon: 'file' },
      { when: '03/11/2026 09:52', who: 'Rim Lahlou', what: 'Paiement 2 000 MAD (carte)', icon: 'cash' },
      { when: '03/11/2026 10:05', who: 'Rim Lahlou', what: 'Check-list départ validée', icon: 'check' },
      { when: '03/11/2026 10:12', who: 'Système',    what: 'Statut → En cours', icon: 'play' },
      { when: '07/11/2026 16:20', who: 'Anas Belkadi', what: 'Paiement 1 000 MAD (espèces)', icon: 'cash' },
    ],
  };

  const bookings = [
    { id: 'RSV-1082', client: 'Lina Haddad', vehicle: 'Peugeot 3008', start: '2026-11-20', end: '2026-11-27', days: 7, estimate: 4550, status: 'confirmed', source: 'website' },
    { id: 'RSV-1081', client: 'Omar Fassi Fihri', vehicle: 'Renault Clio 5', start: '2026-11-15', end: '2026-11-18', days: 3, estimate: 960, status: 'pending', source: 'mobile' },
    { id: 'RSV-1080', client: 'Julien Morel', vehicle: 'Volkswagen Golf 8', start: '2026-11-12', end: '2026-11-19', days: 7, estimate: 3850, status: 'converted', source: 'website' },
    { id: 'RSV-1079', client: 'Sara Benjelloun', vehicle: 'Kia Picanto', start: '2026-11-22', end: '2026-11-24', days: 2, estimate: 440, status: 'pending', source: 'website' },
    { id: 'RSV-1078', client: 'Mehdi Alaoui', vehicle: 'Dacia Duster', start: '2026-11-09', end: '2026-11-10', days: 1, estimate: 420, status: 'cancelled', source: 'backoffice' },
  ];

  const payments = [
    { date: '10/11/2026', contract: 'CTR-2026-0418', client: 'Youssef El Amrani', amount: 1750, method: 'cash', status: 'paid', account: 'Caisse agence' },
    { date: '09/11/2026', contract: 'CTR-2026-0416', client: 'Lina Haddad', amount: 8500, method: 'card', status: 'paid', account: 'Banque — CIH' },
    { date: '09/11/2026', contract: 'CTR-2026-0420', client: 'Omar Fassi Fihri', amount: 1000, method: 'transfer', status: 'partial', account: 'Banque — CIH' },
    { date: '08/11/2026', contract: 'CTR-2026-0415', client: 'Nadia Berrada', amount: 2000, method: 'card', status: 'partial', account: 'Banque — CIH' },
    { date: '07/11/2026', contract: 'CTR-2026-0413', client: 'Karim Ouazzani', amount: 1000, method: 'cash', status: 'partial', account: 'Caisse agence' },
    { date: '05/11/2026', contract: 'CTR-2026-0414', client: 'Sara Benjelloun', amount: 2100, method: 'cheque', status: 'paid', account: 'Banque — CIH' },
  ];

  // FinancialTransaction categories (TransactionCategory) — expenses
  const expenseCategories = [
    { label: 'Crédits véhicules', value: 38600 },
    { label: 'Entretien & vidanges', value: 12450 },
    { label: 'Carburant & lavage', value: 6820 },
    { label: 'Salaires', value: 31500 },
    { label: 'Assurances', value: 9400 },
    { label: 'Loyer & charges', value: 8000 },
  ];

  // 12 months (Déc. 2025 → Nov. 2026, Nov = month-to-date)
  const months = ['Déc.', 'Janv.', 'Févr.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.'];
  const revenue  = [142300, 118600, 109400, 131900, 156200, 168700, 198400, 241800, 263500, 187900, 172600, 64850];
  const expenses = [ 98400,  91200,  88700,  95300, 101800, 104600, 112900, 121400, 127300, 109800, 106770, 41200];
  const contractsPerMonth = [54, 46, 41, 52, 61, 66, 78, 96, 104, 74, 69, 27];
  const occupancy30 = [71,73,70,68,72,75,78,80,79,77,74,72,70,73,76,79,82,84,83,80,78,76,75,77,79,81,83,82,80,78];
  const days30 = Array.from({ length: 30 }, (_, i) => { const d = new Date(2026, 9, 12 + i); return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}`; });
  const weekdays = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
  const pickupsByWeekday = [9, 7, 8, 11, 19, 24, 14];

  const fleetStatus = [
    { key: 'rented',      label: 'En location',  value: 23 },
    { key: 'available',   label: 'Disponibles',  value: 11 },
    { key: 'maintenance', label: 'Maintenance',  value: 5 },
    { key: 'unavailable', label: 'Indisponibles',value: 3 },
  ];
  const paymentMethods = [
    { label: 'Espèces', value: 42 }, { label: 'Carte bancaire', value: 31 },
    { label: 'Virement', value: 19 }, { label: 'Chèque', value: 8 },
  ];
  const sources = [ { label: 'Backoffice', value: 48 }, { label: 'Site web', value: 37 }, { label: 'Mobile', value: 15 } ];
  const topModels = [
    { label: 'Dacia Logan', value: 31 }, { label: 'Renault Clio 5', value: 26 }, { label: 'Peugeot 208', value: 22 },
    { label: 'Hyundai Accent', value: 18 }, { label: 'Dacia Duster', value: 15 }, { label: 'Toyota Corolla', value: 12 },
  ];

  const kpis = {
    revenueMonth: 172600, revenueDelta: -8.1,     // Oct. vs Sept.
    revenueMTD: 64850, revenueMTDDelta: 12.4,     // vs same period Oct.
    activeContracts: 23, activeDelta: 4,
    occupancy: 78, occupancyDelta: 3.2,
    fleet: 42, available: 11,
    pendingPayments: 24300, pendingCount: 7,
    newClients: 37, newClientsDelta: 9,
    bookingsPending: 6,
    avgDuration: 6.4, avgBasket: 2480,
    netMargin: 65830,
  };

  // Alerts derived from vehicle-documents modules + credits + blacklist
  const alerts = [
    { type: 'vidange',   tone: 'warning',  title: 'Vidange à prévoir',            detail: 'Dacia Duster · 72904-H-1 · 64 980 / 65 000 km', due: 'Dans 20 km' },
    { type: 'insurance', tone: 'critical', title: 'Assurance expire bientôt',      detail: 'Renault Clio 5 · 51877-B-6', due: '22/11/2026' },
    { type: 'insurance', tone: 'warning',  title: 'Assurance expire bientôt',      detail: 'Toyota Corolla · 27718-W-6', due: '28/11/2026' },
    { type: 'ctrl',      tone: 'warning',  title: 'Contrôle technique',            detail: 'Dacia Duster · 72904-H-1', due: '18/11/2026' },
    { type: 'credit',    tone: 'critical', title: 'Échéance de crédit en retard',  detail: 'Mercedes Classe A · CRD-0007 · 6 400 MAD', due: 'Retard 5 j' },
    { type: 'vignette',  tone: 'info',     title: 'Vignettes 2027',                detail: '12 véhicules · à renouveler avant le 31/01/2027', due: '31/01/2027' },
  ];

  const notifications = [
    { type: 'contract', title: 'Nouveau contrat',      msg: 'CTR-2026-0418 créé par Anas Belkadi',              ago: 'il y a 12 min', unread: true },
    { type: 'booking',  title: 'Réservation en ligne', msg: 'RSV-1081 — Omar Fassi Fihri · Renault Clio 5',      ago: 'il y a 38 min', unread: true },
    { type: 'vidange',  title: 'Vidange',              msg: 'Dacia Duster 72904-H-1 atteint le seuil de vidange', ago: 'il y a 1 h',    unread: true },
    { type: 'payment',  title: 'Paiement reçu',        msg: '8 500 MAD — CTR-2026-0416 (carte)',                  ago: 'il y a 3 h',    unread: false },
    { type: 'blacklist',title: 'Client blacklisté',    msg: 'Tentative de réservation par Mehdi Alaoui (CIN BH530126)', ago: 'hier', unread: false },
    { type: 'login',    title: 'Connexion',            msg: 'Rim Lahlou s’est connectée',                         ago: 'hier',          unread: false },
  ];

  const credits = [
    { number: 'CRD-0007', vehicle: 'Mercedes Classe A', creditor: 'Wafasalaf', total: 384000, monthly: 6400, remaining: 211200, months: '33 / 60', status: 'late' },
    { number: 'CRD-0005', vehicle: 'Peugeot 3008', creditor: 'Sofac', total: 296000, monthly: 5480, remaining: 120560, months: '22 / 54', status: 'active' },
    { number: 'CRD-0004', vehicle: 'Volkswagen Golf 8', creditor: 'Eqdom', total: 258000, monthly: 4300, remaining: 189200, months: '44 / 60', status: 'active' },
  ];

  window.DATA = {
    agency, currentUser, vehicles, clients, agents, ROLES, contracts, contractDetail, bookings, payments,
    expenseCategories, months, revenue, expenses, contractsPerMonth, occupancy30, days30, weekdays, pickupsByWeekday,
    fleetStatus, paymentMethods, sources, topModels, kpis, alerts, notifications, credits,
    CONTRACT_STATUS, VEHICLE_STATUS, BOOKING_STATUS, PAYMENT_STATUS, PAYMENT_METHOD, SOURCE,
    vehicleName, fmtMAD, fmtNum, fmtK,
    client: (id) => clients.find((c) => c.id === id),
    vehicle: (id) => vehicles.find((v) => v.id === id),
    agent: (id) => agents.find((a) => a.id === id),
    fmtDate: (iso) => { const [y, m, d] = iso.split('-'); return `${d}/${m}/${y}`; },
  };
})();
