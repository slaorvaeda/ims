{{--
  login-animation.blade.php
  Ultra-Realistic Mega-Warehouse Logistics Simulation (Full Height)
  Expanded into a massive, colossal multi-bay high-tech distribution facility.
--}}
<div class="ims-anim-full" style="width:100%;height:100%;min-height:580px;position:relative;overflow:hidden;background:#0F172A;">

  <svg viewBox="0 0 600 520" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;min-height:580px;display:block;" aria-label="IMS Colossal Mega Warehouse Logistics Simulation">

    <defs>
      <!-- Atmospheric Morning Horizon Sky -->
      <linearGradient id="mw-sky" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#38BDF8"/>
        <stop offset="25%" stop-color="#7DD3FC"/>
        <stop offset="55%" stop-color="#BAE6FD"/>
        <stop offset="80%" stop-color="#E0F2FE"/>
        <stop offset="100%" stop-color="#F1F5F9"/>
      </linearGradient>

      <!-- Mega Warehouse Architectural Cladding Panels -->
      <linearGradient id="mw-facade-main" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" stop-color="#E2E8F0"/>
        <stop offset="20%" stop-color="#FFFFFF"/>
        <stop offset="50%" stop-color="#F8FAFC"/>
        <stop offset="80%" stop-color="#FFFFFF"/>
        <stop offset="100%" stop-color="#CBD5E1"/>
      </linearGradient>

      <!-- Warehouse Dark Anthracite Accent Wings -->
      <linearGradient id="mw-facade-dark" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#334155"/>
        <stop offset="45%" stop-color="#1E293B"/>
        <stop offset="100%" stop-color="#0F172A"/>
      </linearGradient>

      <!-- Roof Heavy Structural Girder Beam -->
      <linearGradient id="mw-roof-beam" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#64748B"/>
        <stop offset="35%" stop-color="#334155"/>
        <stop offset="100%" stop-color="#0F172A"/>
      </linearGradient>

      <!-- Operations Mezzanine Solar Reflective Glass -->
      <linearGradient id="mw-curtain-glass" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#0284C7" stop-opacity="0.9"/>
        <stop offset="40%" stop-color="#0369A1" stop-opacity="0.95"/>
        <stop offset="100%" stop-color="#0F172A" stop-opacity="0.98"/>
      </linearGradient>

      <!-- Concrete Docking Apron -->
      <linearGradient id="mw-apron" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#E2E8F0"/>
        <stop offset="30%" stop-color="#CBD5E1"/>
        <stop offset="70%" stop-color="#94A3B8"/>
        <stop offset="100%" stop-color="#64748B"/>
      </linearGradient>

      <!-- Asphalt Multi-Lane Highway -->
      <linearGradient id="mw-road" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#334155"/>
        <stop offset="15%" stop-color="#1E293B"/>
        <stop offset="75%" stop-color="#0F172A"/>
        <stop offset="100%" stop-color="#020617"/>
      </linearGradient>

      <!-- Semi-Truck Container Paint (Industrial Orange) -->
      <linearGradient id="mw-container-orange" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#FF7A58"/>
        <stop offset="25%" stop-color="#FF5A36"/>
        <stop offset="75%" stop-color="#E04020"/>
        <stop offset="100%" stop-color="#9A2208"/>
      </linearGradient>

      <!-- Cab Aerodynamic Paint -->
      <linearGradient id="mw-cab-paint" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#64748B"/>
        <stop offset="35%" stop-color="#334155"/>
        <stop offset="85%" stop-color="#1E293B"/>
        <stop offset="100%" stop-color="#0F172A"/>
      </linearGradient>

      <!-- Polished Chrome Metals -->
      <linearGradient id="mw-chrome" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" stop-color="#E2E8F0"/>
        <stop offset="40%" stop-color="#FFFFFF"/>
        <stop offset="70%" stop-color="#94A3B8"/>
        <stop offset="100%" stop-color="#CBD5E1"/>
      </linearGradient>

      <!-- Amazon Prime EDV Matte Finish -->
      <linearGradient id="mw-amz-body" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#2E3C4E"/>
        <stop offset="40%" stop-color="#1E2836"/>
        <stop offset="100%" stop-color="#0D131A"/>
      </linearGradient>

      <!-- Flipkart Express Signature Royal Blue -->
      <linearGradient id="mw-fk-body" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#0284C7"/>
        <stop offset="40%" stop-color="#0369A1"/>
        <stop offset="100%" stop-color="#075985"/>
      </linearGradient>

      <!-- Volumetric Headlight Light Beam -->
      <linearGradient id="mw-headlight" x1="0%" y1="50%" x2="100%" y2="50%">
        <stop offset="0%" stop-color="#FEF08A" stop-opacity="0.45"/>
        <stop offset="40%" stop-color="#FEF08A" stop-opacity="0.18"/>
        <stop offset="100%" stop-color="#FEF08A" stop-opacity="0"/>
      </linearGradient>

      <!-- Warehouse Interior High-Bay Lighting Glow -->
      <linearGradient id="mw-bay-glow" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#FEF3C7" stop-opacity="0.55"/>
        <stop offset="45%" stop-color="#FDE68A" stop-opacity="0.22"/>
        <stop offset="100%" stop-color="#020617" stop-opacity="0.95"/>
      </linearGradient>

      <!-- Exterior High-Bay Floodlight Beam -->
      <linearGradient id="mw-flood-beam" x1="50%" y1="0%" x2="50%" y2="100%">
        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.4"/>
        <stop offset="60%" stop-color="#FDE047" stop-opacity="0.12"/>
        <stop offset="100%" stop-color="#FDE047" stop-opacity="0"/>
      </linearGradient>

      <!-- Cardboard Carton Shading -->
      <linearGradient id="mw-box-1" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#F59E0B"/>
        <stop offset="100%" stop-color="#B45309"/>
      </linearGradient>
      <linearGradient id="mw-box-2" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D97706"/>
        <stop offset="100%" stop-color="#92400E"/>
      </linearGradient>

      <clipPath id="mw-clip">
        <rect width="600" height="520"/>
      </clipPath>
    </defs>

    <style>
      /* Semi-Truck Motion */
      @keyframes mw-truck {
        0%       { transform: translateX(-300px); opacity: 1; }
        12%      { transform: translateX(25px);   opacity: 1; }
        13.5%    { transform: translateX(23px);   opacity: 1; } /* Brake recoil */
        15%      { transform: translateX(24px);   opacity: 1; } /* Docked rest */
        38%      { transform: translateX(24px);   opacity: 1; }
        40%      { transform: translateX(27px);   opacity: 1; } /* Takeoff squat */
        48%      { transform: translateX(650px);  opacity: 1; }
        48.01%   { transform: translateX(650px);  opacity: 0; }
        48.02%   { transform: translateX(-300px); opacity: 0; }
        99.9%    { transform: translateX(-300px); opacity: 0; }
        100%     { transform: translateX(-300px); opacity: 1; }
      }

      /* Amazon Prime Rivian EDV */
      @keyframes mw-amz {
        0%, 48%  { transform: translateX(-220px); opacity: 0; }
        48.1%    { transform: translateX(-220px); opacity: 1; }
        56%      { transform: translateX(360px);  opacity: 1; }
        57.5%    { transform: translateX(358px);  opacity: 1; } /* Settle */
        59%      { transform: translateX(359px);  opacity: 1; }
        69%      { transform: translateX(359px);  opacity: 1; }
        76%      { transform: translateX(650px);  opacity: 1; }
        76.01%   { transform: translateX(650px);  opacity: 0; }
        100%     { transform: translateX(-220px); opacity: 0; }
      }

      /* Flipkart Express Van */
      @keyframes mw-fk {
        0%, 76%  { transform: translateX(-220px); opacity: 0; }
        76.1%    { transform: translateX(-220px); opacity: 1; }
        83%      { transform: translateX(360px);  opacity: 1; }
        84.5%    { transform: translateX(358px);  opacity: 1; }
        86%      { transform: translateX(359px);  opacity: 1; }
        92%      { transform: translateX(359px);  opacity: 1; }
        99%      { transform: translateX(650px);  opacity: 1; }
        99.01%   { transform: translateX(650px);  opacity: 0; }
        100%     { transform: translateX(-220px); opacity: 0; }
      }

      /* Automated Dock Shutter Doors */
      @keyframes mw-shutter-1 {
        0%, 12%   { transform: scaleY(1); }
        16%, 35%  { transform: scaleY(0.12); }
        38%, 100% { transform: scaleY(1); }
      }
      @keyframes mw-shutter-3 {
        0%, 55%   { transform: scaleY(1); }
        58%, 69%  { transform: scaleY(0.12); }
        72%, 82%  { transform: scaleY(1); }
        85%, 92%  { transform: scaleY(0.12); }
        95%, 100% { transform: scaleY(1); }
      }

      /* Hydraulic Dock Levelers */
      @keyframes mw-ramp-1 {
        0%, 14%   { transform: rotate(0deg); }
        16%, 36%  { transform: rotate(-3.5deg); }
        38%, 100% { transform: rotate(0deg); }
      }
      @keyframes mw-ramp-3 {
        0%, 57%   { transform: rotate(0deg); }
        59%, 69%  { transform: rotate(-3deg); }
        72%, 84%  { transform: rotate(0deg); }
        86%, 92%  { transform: rotate(-3deg); }
        95%, 100% { transform: rotate(0deg); }
      }

      /* Dock Traffic Lights */
      @keyframes mw-signal-1-red {
        0%, 14%    { opacity: 0.2; }
        15%, 37%   { opacity: 1; filter: drop-shadow(0 0 5px #EF4444); }
        38%, 100%  { opacity: 0.2; }
      }
      @keyframes mw-signal-1-green {
        0%, 14%    { opacity: 1; filter: drop-shadow(0 0 5px #22C55E); }
        15%, 37%   { opacity: 0.2; }
        38%, 100%  { opacity: 1; filter: drop-shadow(0 0 5px #22C55E); }
      }

      @keyframes mw-signal-3-red {
        0%, 57%    { opacity: 0.2; }
        58%, 70%   { opacity: 1; filter: drop-shadow(0 0 5px #EF4444); }
        71%, 84%   { opacity: 0.2; }
        85%, 93%   { opacity: 1; filter: drop-shadow(0 0 5px #EF4444); }
        94%, 100%  { opacity: 0.2; }
      }
      @keyframes mw-signal-3-green {
        0%, 57%    { opacity: 1; filter: drop-shadow(0 0 5px #22C55E); }
        58%, 70%   { opacity: 0.2; }
        71%, 84%   { opacity: 1; filter: drop-shadow(0 0 5px #22C55E); }
        85%, 93%   { opacity: 0.2; }
        94%, 100%  { opacity: 1; filter: drop-shadow(0 0 5px #22C55E); }
      }

      /* Inbound Cargo Boxes (Container -> Bay 01 Warehouse) */
      @keyframes mw-box-in-1 {
        0%, 17%  { transform: translate(168px, 320px) scale(0.9); opacity: 0; }
        19%      { transform: translate(175px, 316px) scale(1);   opacity: 1; }
        24%      { transform: translate(236px, 318px); opacity: 1; }
        27%      { transform: translate(244px, 332px) scale(0.85); opacity: 0; }
        100%     { opacity: 0; }
      }
      @keyframes mw-box-in-2 {
        0%, 23%  { transform: translate(154px, 320px) scale(0.9); opacity: 0; }
        25%      { transform: translate(162px, 316px) scale(1);   opacity: 1; }
        30%      { transform: translate(236px, 318px); opacity: 1; }
        33%      { transform: translate(244px, 332px) scale(0.85); opacity: 0; }
        100%     { opacity: 0; }
      }
      @keyframes mw-box-in-3 {
        0%, 29%  { transform: translate(180px, 320px) scale(0.9); opacity: 0; }
        31%      { transform: translate(188px, 316px) scale(1);   opacity: 1; }
        35%      { transform: translate(236px, 318px); opacity: 1; }
        37%      { transform: translate(244px, 332px) scale(0.85); opacity: 0; }
        100%     { opacity: 0; }
      }

      /* Outbound Parcels (Bay 03 -> Amazon EDV) */
      @keyframes mw-box-amz-1 {
        0%, 60%  { transform: translate(355px, 332px) scale(0.85); opacity: 0; }
        62%      { transform: translate(358px, 318px) scale(1);    opacity: 1; }
        66%      { transform: translate(412px, 320px); opacity: 1; }
        68%      { transform: translate(420px, 330px) scale(0.9);  opacity: 0; }
        100%     { opacity: 0; }
      }
      @keyframes mw-box-amz-2 {
        0%, 65%  { transform: translate(355px, 332px) scale(0.85); opacity: 0; }
        67%      { transform: translate(358px, 318px) scale(1);    opacity: 1; }
        70%      { transform: translate(415px, 320px); opacity: 1; }
        72%      { transform: translate(422px, 330px) scale(0.9);  opacity: 0; }
        100%     { opacity: 0; }
      }

      /* Outbound Parcels (Bay 03 -> Flipkart) */
      @keyframes mw-box-fk-1 {
        0%, 86%  { transform: translate(355px, 332px) scale(0.85); opacity: 0; }
        87.5%    { transform: translate(358px, 318px) scale(1);    opacity: 1; }
        90.5%    { transform: translate(412px, 320px); opacity: 1; }
        92%      { transform: translate(420px, 330px) scale(0.9);  opacity: 0; }
        100%     { opacity: 0; }
      }
      @keyframes mw-box-fk-2 {
        0%, 89%  { transform: translate(355px, 332px) scale(0.85); opacity: 0; }
        90.5%    { transform: translate(358px, 318px) scale(1);    opacity: 1; }
        93%      { transform: translate(415px, 320px); opacity: 1; }
        94%      { transform: translate(422px, 330px) scale(0.9);  opacity: 0; }
        100%     { opacity: 0; }
      }

      /* Highway Center Lane Dash Motion */
      @keyframes mw-road-dash {
        from { transform: translateX(0); }
        to   { transform: translateX(-95px); }
      }

      /* Dual Robotic Crane Lifts in Center ASRS */
      @keyframes mw-crane-lift-1 {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-56px); }
      }
      @keyframes mw-crane-lift-2 {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(48px); }
      }

      /* Mezzanine Internal Automated Conveyor Belt Flow */
      @keyframes mw-conveyor-flow {
        from { stroke-dashoffset: 0; }
        to   { stroke-dashoffset: -32; }
      }

      /* Rooftop Industrial Ventilation Fans */
      @keyframes mw-fan-spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
      }

      /* Dispatch Acceleration Flash */
      @keyframes mw-burst-amz {
        0%, 72%     { opacity: 0; transform: scale(0.6); }
        74.5%       { opacity: 1; transform: scale(1); }
        77%, 100%   { opacity: 0; transform: scale(1.2); }
      }
      @keyframes mw-burst-fk {
        0%, 96%     { opacity: 0; transform: scale(0.6); }
        98%         { opacity: 1; transform: scale(1); }
        99.5%, 100% { opacity: 0; transform: scale(1.2); }
      }
    </style>

    <g clip-path="url(#mw-clip)">

      <!-- ══════════════════════════════════════════
           1. ATMOSPHERIC HORIZON SKY & SUN
           ══════════════════════════════════════════ -->
      <rect width="600" height="350" fill="url(#mw-sky)"/>

      <!-- High Morning Sun with Warm Glow -->
      <circle cx="535" cy="40" r="70" fill="#FDE047" opacity="0.25" filter="blur(10px)"/>
      <circle cx="535" cy="40" r="32" fill="#FEF08A" opacity="0.88"/>
      <circle cx="535" cy="40" r="16" fill="#FFFFFF" opacity="0.95"/>

      <!-- Ambient Morning Clouds -->
      <g opacity="0.85">
        <ellipse cx="65"  cy="35" rx="48" ry="16" fill="white"/>
        <ellipse cx="102" cy="27" rx="36" ry="20" fill="white"/>
        <ellipse cx="132" cy="35" rx="40" ry="14" fill="white"/>
      </g>
      <g opacity="0.7">
        <ellipse cx="260" cy="24" rx="46" ry="14" fill="white"/>
        <ellipse cx="292" cy="18" rx="36" ry="16" fill="white"/>
        <ellipse cx="322" cy="24" rx="38" ry="13" fill="white"/>
      </g>

      <!-- Distant Industrial Logistics Silhouettes -->
      <polygon points="0,210 35,198 75,204 120,196 160,208 200,200 240,210" fill="#CBD5E1" opacity="0.4"/>
      <polygon points="400,210 440,200 480,206 530,195 570,204 600,210" fill="#CBD5E1" opacity="0.4"/>


      <!-- ══════════════════════════════════════════
           2. COLOSSAL UPGRADED MEGA-WAREHOUSE COMPLEX
              Full-width edge-to-edge (x=-10 to x=610, 322px high!)
           ══════════════════════════════════════════ -->
      <!-- Massive Building Ground Shadow -->
      <rect x="-10" y="346" width="620" height="12" fill="rgba(0,0,0,0.3)"/>

      <!-- ── Far-Left High-Bay Silo Tower Wing (x=-10 to x=135) ── -->
      <rect x="-10" y="32" width="145" height="318" fill="url(#mw-facade-dark)"/>
      <!-- Silo Tower Structural Panel Joints -->
      <line x1="-10" y1="60"  x2="135" y2="60"  stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="90"  x2="135" y2="90"  stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="120" x2="135" y2="120" stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="150" x2="135" y2="150" stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="180" x2="135" y2="180" stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="210" x2="135" y2="210" stroke="#475569" stroke-width="1.2"/>
      <line x1="-10" y1="240" x2="135" y2="240" stroke="#475569" stroke-width="1.2"/>
      <line x1="40"  y1="32"  x2="40"  y2="350" stroke="#475569" stroke-width="1.5"/>
      <line x1="90"  y1="32"  x2="90"  y2="350" stroke="#475569" stroke-width="1.5"/>

      <!-- Left Wing High-Volume Ventilation Grilles -->
      <rect x="52" y="70" width="28" height="28" fill="#0F172A" rx="2" stroke="#64748B" stroke-width="1"/>
      <line x1="52" y1="77" x2="80" y2="77" stroke="#475569" stroke-width="1"/>
      <line x1="52" y1="84" x2="80" y2="84" stroke="#475569" stroke-width="1"/>
      <line x1="52" y1="91" x2="80" y2="91" stroke="#475569" stroke-width="1"/>

      <!-- Left Wing Emergency Fire Exit Door -->
      <rect x="60" y="306" width="22" height="44" fill="#1E293B" rx="1" stroke="#EF4444" stroke-width="1.2"/>
      <rect x="62" y="310" width="18" height="12" fill="#EF4444" opacity="0.3"/>
      <text x="71" y="302" text-anchor="middle" font-size="4.5" font-weight="900" fill="#EF4444" font-family="sans-serif">EMERGENCY EXIT</text>


      <!-- ── Far-Right Distribution & Cold-Chain Wing (x=495 to x=610) ── -->
      <rect x="495" y="32" width="115" height="318" fill="url(#mw-facade-dark)"/>
      <line x1="495" y1="60"  x2="610" y2="60"  stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="90"  x2="610" y2="90"  stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="120" x2="610" y2="120" stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="150" x2="610" y2="150" stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="180" x2="610" y2="180" stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="210" x2="610" y2="210" stroke="#475569" stroke-width="1.2"/>
      <line x1="495" y1="240" x2="610" y2="240" stroke="#475569" stroke-width="1.2"/>
      <line x1="550" y1="32"  x2="550" y2="350" stroke="#475569" stroke-width="1.5"/>

      <!-- Right Wing Ventilation Fan -->
      <rect x="522" y="70" width="28" height="28" fill="#0F172A" rx="2" stroke="#64748B" stroke-width="1"/>
      <line x1="522" y1="77" x2="550" y2="77" stroke="#475569" stroke-width="1"/>
      <line x1="522" y1="84" x2="550" y2="84" stroke="#475569" stroke-width="1"/>
      <line x1="522" y1="91" x2="550" y2="91" stroke="#475569" stroke-width="1"/>

      <!-- Right Wing Staff Security Door -->
      <rect x="524" y="306" width="22" height="44" fill="#1E293B" rx="1" stroke="#38BDF8" stroke-width="1.2"/>
      <rect x="526" y="310" width="18" height="12" fill="#38BDF8" opacity="0.3"/>
      <text x="535" y="302" text-anchor="middle" font-size="4.5" font-weight="900" fill="#38BDF8" font-family="sans-serif">STAFF ACCESS</text>


      <!-- ── Central Mega-Warehouse Main Superstructure (x=130 to x=500) ── -->
      <rect x="130" y="34" width="370" height="316" fill="url(#mw-facade-main)" stroke="#CBD5E1" stroke-width="1.5"/>

      <!-- Architectural Vertical Wall Seam Grooves -->
      <line x1="165" y1="34" x2="165" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="200" y1="34" x2="200" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="235" y1="34" x2="235" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="275" y1="34" x2="275" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="345" y1="34" x2="345" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="385" y1="34" x2="385" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="425" y1="34" x2="425" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>
      <line x1="465" y1="34" x2="465" y2="350" stroke="#E2E8F0" stroke-width="1.2"/>

      <!-- Full-Span Structural Roof Parapet Beam (x=-10 to x=610) -->
      <rect x="-10" y="26" width="620" height="12" fill="url(#mw-roof-beam)"/>
      <line x1="-10" y1="26" x2="610" y2="26" stroke="#94A3B8" stroke-width="2.5"/>

      <!-- Massive Rooftop Industrial Chillers & HVAC Condenser Banks -->
      <g>
        <rect x="10"  y="12" width="70" height="14" fill="#334155" rx="1.5"/>
        <line x1="35"  y1="12" x2="35"  y2="26" stroke="#64748B" stroke-width="1"/>
        <line x1="55"  y1="12" x2="55"  y2="26" stroke="#64748B" stroke-width="1"/>
        <!-- Dual Chiller Fans -->
        <circle cx="25" cy="19" r="4.5" fill="#1E293B" stroke="#64748B" stroke-width="0.8"/>
        <circle cx="45" cy="19" r="4.5" fill="#1E293B" stroke="#64748B" stroke-width="0.8"/>

        <rect x="495" y="12" width="90" height="14" fill="#334155" rx="1.5"/>
        <line x1="525" y1="12" x2="525" y2="26" stroke="#64748B" stroke-width="1"/>
        <line x1="555" y1="12" x2="555" y2="26" stroke="#64748B" stroke-width="1"/>
        <circle cx="510" cy="19" r="4.5" fill="#1E293B" stroke="#64748B" stroke-width="0.8"/>
        <circle cx="540" cy="19" r="4.5" fill="#1E293B" stroke="#64748B" stroke-width="0.8"/>
        <circle cx="570" cy="19" r="4.5" fill="#1E293B" stroke="#64748B" stroke-width="0.8"/>
      </g>

      <!-- Rooftop Solar PV Arrays Across Central Roof -->
      <g opacity="0.85">
        <rect x="145" y="16" width="60" height="10" fill="#1E293B" stroke="#38BDF8" stroke-width="0.8"/>
        <line x1="165" y1="16" x2="165" y2="26" stroke="#38BDF8" stroke-width="0.8"/>
        <line x1="185" y1="16" x2="185" y2="26" stroke="#38BDF8" stroke-width="0.8"/>

        <rect x="425" y="16" width="60" height="10" fill="#1E293B" stroke="#38BDF8" stroke-width="0.8"/>
        <line x1="445" y1="16" x2="445" y2="26" stroke="#38BDF8" stroke-width="0.8"/>
        <line x1="465" y1="16" x2="465" y2="26" stroke="#38BDF8" stroke-width="0.8"/>
      </g>

      <!-- Twin Telecommunication Masts & Blinking Aircraft Safety Beacons -->
      <line x1="8"   y1="26" x2="8"   y2="-4" stroke="#64748B" stroke-width="2"/>
      <circle cx="8"   cy="-6" r="3" fill="#EF4444">
        <animate attributeName="opacity" values="1;0.2;1" dur="0.9s" repeatCount="indefinite"/>
      </circle>
      <line x1="592" y1="26" x2="592" y2="-4" stroke="#64748B" stroke-width="2"/>
      <circle cx="592" cy="-6" r="3" fill="#EF4444">
        <animate attributeName="opacity" values="1;0.2;1" dur="0.9s" begin="0.45s" repeatCount="indefinite"/>
      </circle>


      <!-- ══════════════════════════════════════════
           GRAND ILLUMINATED MARQUEE HEADER: "WAREHOUSE"
           ══════════════════════════════════════════ -->
      <g>
        <!-- Marquee Halo Glow -->
        <rect x="160" y="36" width="280" height="34" rx="5" fill="#FF5A36" opacity="0.18" filter="blur(6px)"/>
        <!-- Main Marquee Fascia -->
        <rect x="160" y="36" width="280" height="34" fill="#0F172A" rx="4" stroke="#FF5A36" stroke-width="2"/>

        <!-- Bold Master Title: "WAREHOUSE" -->
        <text x="300" y="55" text-anchor="middle" font-size="16" font-weight="900" fill="white" font-family="'Outfit', sans-serif" letter-spacing="4">WAREHOUSE</text>
        <!-- Sub-Label Specification -->
      </g>


      <!-- ══════════════════════════════════════════
           LEVEL 2: FULL-SPAN MEZZANINE OPERATIONS DECK
           Edge-to-edge panoramic glass control deck
           ══════════════════════════════════════════ -->
      <rect x="138" y="78" width="354" height="48" fill="url(#mw-curtain-glass)" rx="2" stroke="#0369A1" stroke-width="1.2"/>

      <!-- Architectural Glass Mullions -->
      <line x1="182" y1="78" x2="182" y2="126" stroke="#0F172A" stroke-width="1.8"/>
      <line x1="226" y1="78" x2="226" y2="126" stroke="#0F172A" stroke-width="1.8"/>
      <line x1="270" y1="78" x2="270" y2="126" stroke="#0F172A" stroke-width="2"/>
      <line x1="314" y1="78" x2="314" y2="126" stroke="#0F172A" stroke-width="2"/>
      <line x1="358" y1="78" x2="358" y2="126" stroke="#0F172A" stroke-width="1.8"/>
      <line x1="402" y1="78" x2="402" y2="126" stroke="#0F172A" stroke-width="1.8"/>
      <line x1="446" y1="78" x2="446" y2="126" stroke="#0F172A" stroke-width="1.8"/>

      <!-- Glowing Mezzanine Dispatch Dashboards & High-Tech Server Hubs -->
      <rect x="150" y="88" width="24" height="24" fill="#38BDF8" opacity="0.75" rx="1"/>
      <rect x="194" y="90" width="24" height="20" fill="#4ADE80" opacity="0.65" rx="1"/>
      <rect x="238" y="90" width="24" height="20" fill="#FACC15" opacity="0.65" rx="1"/>
      <rect x="326" y="90" width="24" height="20" fill="#F43F5E" opacity="0.65" rx="1"/>
      <rect x="370" y="90" width="24" height="20" fill="#38BDF8" opacity="0.7" rx="1"/>
      <rect x="414" y="88" width="24" height="24" fill="#4ADE80" opacity="0.75" rx="1"/>
      <rect x="458" y="88" width="24" height="24" fill="#38BDF8" opacity="0.7" rx="1"/>

      <!-- Visible Internal Conveyor Belt Sorter Flow Behind Mezzanine Glass -->
      <line x1="140" y1="120" x2="490" y2="120" stroke="#0F172A" stroke-width="3"/>
      <line x1="140" y1="120" x2="490" y2="120" stroke="#FDE047" stroke-width="2" stroke-dasharray="6 10" style="animation: mw-conveyor-flow 0.8s linear infinite;"/>


      <!-- ══════════════════════════════════════════
           CENTER AUTOMATED STORAGE & RETRIEVAL SYSTEM (ASRS)
           Multi-level vertical robotic warehouse chamber
           ══════════════════════════════════════════ -->
      <rect x="272" y="132" width="66" height="218" fill="#020617" stroke="#334155" stroke-width="1"/>
      <rect x="272" y="132" width="66" height="218" fill="url(#mw-bay-glow)"/>

      <!-- ASRS Heavy Structural Vertical Rails -->
      <line x1="282" y1="132" x2="282" y2="350" stroke="#475569" stroke-width="1.8"/>
      <line x1="328" y1="132" x2="328" y2="350" stroke="#475569" stroke-width="1.8"/>

      <!-- 7 Tiers of Heavy-Duty High-Bay Pallet Shelving -->
      <line x1="274" y1="155" x2="336" y2="155" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="180" x2="336" y2="180" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="205" x2="336" y2="205" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="230" x2="336" y2="230" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="255" x2="336" y2="255" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="280" x2="336" y2="280" stroke="#EA580C" stroke-width="2"/>
      <line x1="274" y1="305" x2="336" y2="305" stroke="#EA580C" stroke-width="2"/>

      <!-- High-Density Palletized Inventory -->
      <rect x="275" y="142" width="13" height="11" fill="#D97706" rx="1"/>
      <rect x="320" y="142" width="13" height="11" fill="#B45309" rx="1"/>
      <rect x="275" y="192" width="13" height="11" fill="#F59E0B" rx="1"/>
      <rect x="320" y="192" width="13" height="11" fill="#D97706" rx="1"/>
      <rect x="275" y="242" width="13" height="11" fill="#B45309" rx="1"/>
      <rect x="320" y="242" width="13" height="11" fill="#F59E0B" rx="1"/>
      <rect x="275" y="292" width="13" height="11" fill="#D97706" rx="1"/>
      <rect x="320" y="292" width="13" height="11" fill="#B45309" rx="1"/>

      <!-- Dual Robotic Crane Shuttles (ASRS Automation) -->
      <g style="animation: mw-crane-lift-1 5s ease-in-out infinite;">
        <rect x="284" y="218" width="42" height="10" fill="#FF5A36" rx="1.5"/>
        <line x1="284" y1="223" x2="326" y2="223" stroke="#FFFFFF" stroke-width="1"/>
        <rect x="298" y="211" width="14" height="8" fill="#FDE047" rx="1"/>
      </g>
      <g style="animation: mw-crane-lift-2 5s ease-in-out infinite;">
        <rect x="284" y="165" width="42" height="10" fill="#0284C7" rx="1.5"/>
        <line x1="284" y1="170" x2="326" y2="170" stroke="#FFFFFF" stroke-width="1"/>
        <rect x="298" y="158" width="14" height="8" fill="#38BDF8" rx="1"/>
      </g>

      <!-- Center Floor Glass Lobby Door -->
      <rect x="286" y="322" width="38" height="28" fill="#1E293B" rx="1" stroke="#475569" stroke-width="1"/>
      <rect x="290" y="326" width="13" height="24" fill="#7DD3FC" opacity="0.6"/>
      <rect x="307" y="326" width="13" height="24" fill="#7DD3FC" opacity="0.6"/>


      <!-- ══════════════════════════════════════════
           3. FOUR HIGH-CAPACITY DOCK BAYS
           ══════════════════════════════════════════ -->

      <!-- ── BAY 01: EXPANDED INBOUND RECEIVING DOCK (x=138 to x=234) ── -->
      <!-- Compression Dock Shelter -->
      <rect x="136" y="172" width="98" height="178" fill="#0F172A" rx="3"/>
      <rect x="138" y="174" width="6" height="174" fill="#FACC15"/>
      <rect x="226" y="174" width="6" height="174" fill="#FACC15"/>

      <!-- Bay 01 Interior Cavity -->
      <rect x="148" y="186" width="74" height="164" fill="#020617"/>
      <rect x="148" y="186" width="74" height="164" fill="url(#mw-bay-glow)"/>

      <!-- Multi-Tier Receiving Pallet Storage Racks -->
      <line x1="152" y1="220" x2="218" y2="220" stroke="#EA580C" stroke-width="2.5"/>
      <line x1="152" y1="255" x2="218" y2="255" stroke="#EA580C" stroke-width="2.5"/>
      <line x1="152" y1="290" x2="218" y2="290" stroke="#EA580C" stroke-width="2.5"/>
      <line x1="152" y1="325" x2="218" y2="325" stroke="#EA580C" stroke-width="2.5"/>
      <rect x="154" y="206" width="18" height="13" fill="#D97706" rx="1"/>
      <rect x="178" y="206" width="18" height="13" fill="#B45309" rx="1"/>
      <rect x="156" y="241" width="17" height="13" fill="#F59E0B" rx="1"/>
      <rect x="182" y="241" width="18" height="13" fill="#D97706" rx="1"/>
      <rect x="154" y="276" width="19" height="13" fill="#B45309" rx="1"/>
      <rect x="180" y="276" width="17" height="13" fill="#F59E0B" rx="1"/>

      <!-- Roll-Up Industrial Shutter Door Bay 01 -->
      <g style="animation: mw-shutter-1 20s ease-in-out infinite; transform-origin: 185px 186px;">
        <rect x="148" y="186" width="74" height="164" fill="#334155" rx="1"/>
        <line x1="148" y1="206" x2="222" y2="206" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="226" x2="222" y2="226" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="246" x2="222" y2="246" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="266" x2="222" y2="266" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="286" x2="222" y2="286" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="306" x2="222" y2="306" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="326" x2="222" y2="326" stroke="#1E293B" stroke-width="2"/>
        <line x1="148" y1="344" x2="222" y2="344" stroke="#1E293B" stroke-width="2"/>
        <!-- Window lites -->
        <rect x="156" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
        <rect x="177" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
        <rect x="198" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
      </g>

      <!-- Bay 01 Overhead Digital Marquee -->
      <rect x="146" y="174" width="78" height="11" fill="#1D4ED8"/>
      <text x="185" y="182.5" text-anchor="middle" font-size="6.5" font-weight="900" fill="white" font-family="sans-serif">BAY 01 · INBOUND BULK</text>

      <!-- Active Dock Traffic Signal 1 -->
      <rect x="126" y="236" width="7" height="18" fill="#1E293B" rx="1"/>
      <circle cx="129.5" cy="241" r="2.5" fill="#EF4444" style="animation: mw-signal-1-red 20s infinite;"/>
      <circle cx="129.5" cy="249" r="2.5" fill="#22C55E" style="animation: mw-signal-1-green 20s infinite;"/>

      <!-- Hydraulic Dock Leveler 1 -->
      <g style="animation: mw-ramp-1 20s ease-in-out infinite; transform-origin: 220px 350px;">
        <rect x="146" y="348" width="78" height="5" fill="#475569" rx="1"/>
        <line x1="146" y1="349" x2="224" y2="349" stroke="#E2E8F0" stroke-width="1.2"/>
      </g>
      <rect x="136" y="348" width="10" height="15" fill="#020617" rx="1"/>
      <rect x="224" y="348" width="10" height="15" fill="#020617" rx="1"/>


      <!-- ── BAY 02: HIGH-BAY CROSS-DOCK BUFFER (x=238 to x=282) ── -->
      <rect x="238" y="206" width="44" height="144" fill="#0F172A" rx="2"/>
      <rect x="240" y="208" width="4" height="140" fill="#FACC15"/>
      <rect x="276" y="208" width="4" height="140" fill="#FACC15"/>
      <!-- Roll-Up Shutter Bay 02 -->
      <rect x="244" y="218" width="32" height="132" fill="#334155" rx="1"/>
      <line x1="244" y1="234" x2="276" y2="234" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="250" x2="276" y2="250" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="266" x2="276" y2="266" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="282" x2="276" y2="282" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="298" x2="276" y2="298" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="314" x2="276" y2="314" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="244" y1="330" x2="276" y2="330" stroke="#1E293B" stroke-width="1.8"/>
      <!-- Bay 02 Header -->
      <rect x="242" y="208" width="36" height="9" fill="#0369A1"/>
      <text x="260" y="215" text-anchor="middle" font-size="5" font-weight="900" fill="white" font-family="sans-serif">BAY 02</text>
      <!-- Bumpers -->
      <rect x="238" y="348" width="6" height="14" fill="#020617"/>
      <rect x="276" y="348" width="6" height="14" fill="#020617"/>


      <!-- ── BAY 03: OUTBOUND PRIME FULFILLMENT DOCK (x=340 to x=436) ── -->
      <!-- Compression Dock Shelter -->
      <rect x="338" y="172" width="98" height="178" fill="#0F172A" rx="3"/>
      <rect x="340" y="174" width="6" height="174" fill="#FACC15"/>
      <rect x="428" y="174" width="6" height="174" fill="#FACC15"/>

      <!-- Bay 03 Interior Cavity -->
      <rect x="350" y="186" width="74" height="164" fill="#020617"/>
      <rect x="350" y="186" width="74" height="164" fill="url(#mw-bay-glow)"/>

      <!-- Sorting Staging Line & Outbound Inventory -->
      <line x1="354" y1="260" x2="420" y2="260" stroke="#10B981" stroke-width="2.5"/>
      <line x1="354" y1="295" x2="420" y2="295" stroke="#10B981" stroke-width="2.5"/>
      <rect x="358" y="246" width="20" height="13" fill="#FF9900" rx="1"/>
      <rect x="384" y="246" width="20" height="13" fill="#0284C7" rx="1"/>
      <rect x="358" y="281" width="18" height="13" fill="#F59E0B" rx="1"/>
      <rect x="382" y="281" width="22" height="13" fill="#0284C7" rx="1"/>

      <!-- Roll-Up Industrial Shutter Door Bay 03 -->
      <g style="animation: mw-shutter-3 20s ease-in-out infinite; transform-origin: 387px 186px;">
        <rect x="350" y="186" width="74" height="164" fill="#334155" rx="1"/>
        <line x1="350" y1="206" x2="424" y2="206" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="226" x2="424" y2="226" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="246" x2="424" y2="246" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="266" x2="424" y2="266" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="286" x2="424" y2="286" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="306" x2="424" y2="306" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="326" x2="424" y2="326" stroke="#1E293B" stroke-width="2"/>
        <line x1="350" y1="344" x2="424" y2="344" stroke="#1E293B" stroke-width="2"/>
        <rect x="358" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
        <rect x="379" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
        <rect x="400" y="210" width="16" height="7" fill="#93C5FD" rx="1"/>
      </g>

      <!-- Bay 03 Overhead Digital Marquee -->
      <rect x="348" y="174" width="78" height="11" fill="#15803D"/>
      <text x="387" y="182.5" text-anchor="middle" font-size="6.5" font-weight="900" fill="white" font-family="sans-serif">BAY 03 · OUTBOUND PRIME</text>

      <!-- Active Dock Traffic Signal 3 -->
      <rect x="436" y="236" width="7" height="18" fill="#1E293B" rx="1"/>
      <circle cx="439.5" cy="241" r="2.5" fill="#EF4444" style="animation: mw-signal-3-red 20s infinite;"/>
      <circle cx="439.5" cy="249" r="2.5" fill="#22C55E" style="animation: mw-signal-3-green 20s infinite;"/>

      <!-- Hydraulic Dock Leveler 3 -->
      <g style="animation: mw-ramp-3 20s ease-in-out infinite; transform-origin: 422px 350px;">
        <rect x="348" y="348" width="78" height="5" fill="#475569" rx="1"/>
        <line x1="348" y1="349" x2="426" y2="349" stroke="#E2E8F0" stroke-width="1.2"/>
      </g>
      <rect x="338" y="348" width="10" height="15" fill="#020617" rx="1"/>
      <rect x="426" y="348" width="10" height="15" fill="#020617" rx="1"/>


      <!-- ── BAY 04: EXPRESS PARCEL DISPATCH (x=442 to x=486) ── -->
      <rect x="442" y="206" width="44" height="144" fill="#0F172A" rx="2"/>
      <rect x="444" y="208" width="4" height="140" fill="#FACC15"/>
      <rect x="480" y="208" width="4" height="140" fill="#FACC15"/>
      <!-- Roll-Up Shutter Bay 04 -->
      <rect x="448" y="218" width="32" height="132" fill="#334155" rx="1"/>
      <line x1="448" y1="234" x2="480" y2="234" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="250" x2="480" y2="250" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="266" x2="480" y2="266" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="282" x2="480" y2="282" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="298" x2="480" y2="298" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="314" x2="480" y2="314" stroke="#1E293B" stroke-width="1.8"/>
      <line x1="448" y1="330" x2="480" y2="330" stroke="#1E293B" stroke-width="1.8"/>
      <!-- Bay 04 Header -->
      <rect x="446" y="208" width="36" height="9" fill="#059669"/>
      <text x="464" y="215" text-anchor="middle" font-size="5" font-weight="900" fill="white" font-family="sans-serif">BAY 04</text>
      <!-- Bumpers -->
      <rect x="442" y="348" width="6" height="14" fill="#020617"/>
      <rect x="480" y="348" width="6" height="14" fill="#020617"/>


      <!-- ══════════════════════════════════════════
           4. CONCRETE STAGING APRON & MULTI-LANE HIGHWAY
           ══════════════════════════════════════════ -->
      <!-- Concrete Staging Apron with Expansion Joints -->
      <rect y="350" width="600" height="24" fill="url(#mw-apron)"/>
      <line x1="0"   y1="350" x2="600" y2="350" stroke="#F1F5F9" stroke-width="1.2"/>
      <line x1="60"  y1="350" x2="60"  y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="120" y1="350" x2="120" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="180" y1="350" x2="180" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="240" y1="350" x2="240" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="300" y1="350" x2="300" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="360" y1="350" x2="360" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="420" y1="350" x2="420" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="480" y1="350" x2="480" y2="374" stroke="#475569" stroke-width="1.2"/>
      <line x1="540" y1="350" x2="540" y2="374" stroke="#475569" stroke-width="1.2"/>

      <!-- High-Visibility Yellow & Black Hazard Curb -->
      <rect y="372" width="600" height="5" fill="#FACC15"/>
      <line x1="0" y1="372" x2="600" y2="372" stroke="#000000" stroke-width="5" stroke-dasharray="10 10"/>

      <!-- Asphalt Multi-Lane Highway (144px Full Height Depth) -->
      <rect y="376" width="600" height="144" fill="url(#mw-road)"/>

      <!-- Road Shoulder Solid White Lines -->
      <line x1="0" y1="379" x2="600" y2="379" stroke="white" stroke-width="1.8" opacity="0.4"/>
      <line x1="0" y1="512" x2="600" y2="512" stroke="white" stroke-width="1.8" opacity="0.4"/>

      <!-- Highway Center Dashed Lines (Lane 1 & Lane 2) -->
      <g style="animation: mw-road-dash 1.1s linear infinite;">
        <rect x="-30" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="65"  y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="160" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="255" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="350" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="445" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
        <rect x="540" y="446" width="55" height="4" rx="2" fill="#FDE047" opacity="0.65"/>
      </g>


      <!-- ══════════════════════════════════════════
           5. VEHICLE 1: ORANGE CONTAINER SEMI-TRUCK
           ══════════════════════════════════════════ -->
      <g style="animation: mw-truck 20s cubic-bezier(0.35,0,0.15,1) infinite; will-change: transform;">

        <!-- Headlight Volumetric Light Beam -->
        <polygon points="245,395 380,375 380,445 245,406" fill="url(#mw-headlight)"/>

        <!-- Ground Contact Shadow -->
        <ellipse cx="125" cy="425" rx="130" ry="7" fill="rgba(0,0,0,0.7)" filter="blur(3.5px)"/>

        <!-- Heavy I-Beam Steel Chassis -->
        <rect x="6" y="392" width="238" height="8" fill="#1E293B" rx="1"/>
        <rect x="6" y="396" width="238" height="3" fill="#0F172A"/>

        <!-- ── 40-FOOT HIGH-CUBE ORANGE CONTAINER ── -->
        <rect x="8" y="285" width="176" height="106" fill="url(#mw-container-orange)" rx="3" stroke="#7C1D06" stroke-width="1.5"/>

        <!-- ISO Corner Castings -->
        <rect x="8"   y="285" width="10" height="10" fill="#0F172A" rx="1.5"/>
        <rect x="174" y="285" width="10" height="10" fill="#0F172A" rx="1.5"/>
        <rect x="8"   y="381" width="10" height="10" fill="#0F172A" rx="1.5"/>
        <rect x="174" y="381" width="10" height="10" fill="#0F172A" rx="1.5"/>

        <!-- 3D Corrugated Orange Steel Ribs -->
        <g opacity="0.65">
          <line x1="24"  y1="295" x2="24"  y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="40"  y1="295" x2="40"  y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="56"  y1="295" x2="56"  y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="72"  y1="295" x2="72"  y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="88"  y1="295" x2="88"  y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="104" y1="295" x2="104" y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="120" y1="295" x2="120" y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="136" y1="295" x2="136" y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="152" y1="295" x2="152" y2="381" stroke="#FFB299" stroke-width="2"/>
          <line x1="168" y1="295" x2="168" y2="381" stroke="#FFB299" stroke-width="2"/>
        </g>

        <!-- Container Commercial Decal & Placard Specs -->
        <rect x="46" y="312" width="100" height="28" fill="#0F172A" rx="3" stroke="#FF5A36" stroke-width="1.2"/>
        <text x="96" y="327" text-anchor="middle" font-size="9.5" font-weight="900" fill="white" font-family="'Outfit',sans-serif" letter-spacing="1.2">GLOBAL FREIGHT</text>
        <text x="96" y="336" text-anchor="middle" font-size="6" font-weight="700" fill="#FED7AA" font-family="sans-serif">INTERMODAL CARGO LOGISTICS</text>

        <!-- Spec Badges & Hazmat Diamond -->
        <polygon points="20,362 25,354 30,362 25,370" fill="#EF4444"/>
        <rect x="152" y="360" width="18" height="10" fill="#1E293B" rx="1"/>
        <text x="161" y="367" text-anchor="middle" font-size="4.5" fill="white" font-family="monospace">MAX 32.5T</text>

        <!-- Fifth Wheel Coupling Pin -->
        <rect x="176" y="380" width="10" height="15" fill="#334155" rx="1.5"/>

        <!-- ── COMMERCIAL CAB (PRIME MOVER) ── -->
        <path d="M 184,392 L 184,302 L 218,298 L 246,326 L 250,392 Z" fill="url(#mw-cab-paint)" stroke="#0F172A" stroke-width="1.5"/>

        <!-- Aero Roof Fairing Deflector -->
        <path d="M 184,302 Q 206,286 232,292 L 218,298 Z" fill="#64748B"/>

        <!-- Windshield with Sun Visor & Driver Silhouette -->
        <path d="M 218,302 L 242,326 L 216,326 Z" fill="#38BDF8" opacity="0.85"/>
        <line x1="222" y1="304" x2="236" y2="324" stroke="white" stroke-width="1.8" opacity="0.6"/>
        <circle cx="224" cy="318" r="4" fill="#0F172A" opacity="0.75"/>

        <!-- Side Driver Window -->
        <rect x="188" y="306" width="24" height="18" fill="#38BDF8" opacity="0.75" rx="1"/>
        <line x1="192" y1="308" x2="196" y2="322" stroke="white" stroke-width="1.2" opacity="0.5"/>

        <!-- Chrome Mirrors -->
        <rect x="184" y="308" width="4" height="11" fill="url(#mw-chrome)" rx="1"/>
        <rect x="245" y="318" width="4" height="11" fill="url(#mw-chrome)" rx="1"/>

        <!-- Chrome Front Louvre Grille -->
        <rect x="246" y="342" width="6" height="38" fill="url(#mw-chrome)" rx="1.5"/>
        <line x1="246" y1="348" x2="252" y2="348" stroke="#1E293B" stroke-width="1.2"/>
        <line x1="246" y1="354" x2="252" y2="354" stroke="#1E293B" stroke-width="1.2"/>
        <line x1="246" y1="360" x2="252" y2="360" stroke="#1E293B" stroke-width="1.2"/>
        <line x1="246" y1="366" x2="252" y2="366" stroke="#1E293B" stroke-width="1.2"/>
        <line x1="246" y1="372" x2="252" y2="372" stroke="#1E293B" stroke-width="1.2"/>

        <!-- Headlights -->
        <rect x="247" y="332" width="5" height="8" fill="#FEF08A" rx="1"/>
        <rect x="247" y="382" width="4" height="4" fill="#F59E0B" rx="1"/>

        <!-- Exhaust Stack with Smoke -->
        <rect x="186" y="276" width="4.5" height="26" fill="url(#mw-chrome)" rx="1"/>
        <circle cx="188" cy="270" r="3.5" fill="#94A3B8" opacity="0.45">
          <animate attributeName="r" values="3;7;3" dur="1.5s" repeatCount="indefinite"/>
          <animate attributeName="cy" values="270;252;270" dur="1.5s" repeatCount="indefinite"/>
          <animate attributeName="opacity" values="0.45;0;0.45" dur="1.5s" repeatCount="indefinite"/>
        </circle>

        <!-- Fuel Tanks -->
        <rect x="120" y="398" width="38" height="14" fill="url(#mw-chrome)" rx="6"/>
        <line x1="128" y1="398" x2="128" y2="412" stroke="#334155" stroke-width="1.5"/>
        <line x1="150" y1="398" x2="150" y2="412" stroke="#334155" stroke-width="1.5"/>

        <!-- Wheels -->
        <g transform="translate(28, 414)">
          <circle r="14" fill="#0F172A"/><circle r="11" fill="#1E293B"/><circle r="6" fill="url(#mw-chrome)"/><circle r="2.5" fill="#0F172A"/>
        </g>
        <g transform="translate(56, 414)">
          <circle r="14" fill="#0F172A"/><circle r="11" fill="#1E293B"/><circle r="6" fill="url(#mw-chrome)"/><circle r="2.5" fill="#0F172A"/>
        </g>
        <g transform="translate(190, 414)">
          <circle r="14" fill="#0F172A"/><circle r="11" fill="#1E293B"/><circle r="6" fill="url(#mw-chrome)"/><circle r="2.5" fill="#0F172A"/>
        </g>
        <g transform="translate(234, 414)">
          <circle r="14" fill="#0F172A"/><circle r="11" fill="#1E293B"/><circle r="6" fill="url(#mw-chrome)"/><circle r="2.5" fill="#0F172A"/>
        </g>
      </g>


      <!-- ══════════════════════════════════════════
           6. VEHICLE 2: AMAZON PRIME RIVIAN EDV
           ══════════════════════════════════════════ -->
      <g style="animation: mw-amz 20s cubic-bezier(0.35,0,0.15,1) infinite; will-change: transform;">

        <!-- Headlight Beam -->
        <polygon points="185,398 290,380 290,435 185,406" fill="url(#mw-headlight)"/>

        <!-- Ground Shadow -->
        <ellipse cx="95" cy="424" rx="96" ry="5.5" fill="rgba(0,0,0,0.65)" filter="blur(3px)"/>

        <!-- Rivian EDV Body -->
        <path d="M 4,402 L 4,316 Q 4,308 12,308 L 134,308 L 158,312 Q 184,318 186,346 L 188,402 Z" fill="url(#mw-amz-body)" stroke="#0B0F14" stroke-width="1.5"/>
        <line x1="8" y1="398" x2="184" y2="398" stroke="#00A8E1" stroke-width="2.5"/>

        <!-- Amazon Prime Livery -->
        <rect x="16" y="326" width="112" height="45" fill="#19222D" rx="3" stroke="#2A384A" stroke-width="1"/>
        <text x="72" y="348" text-anchor="middle" font-size="15" font-weight="900" fill="#FFFFFF" font-family="'Plus Jakarta Sans', Arial, sans-serif" letter-spacing="-0.5">prime</text>
        <path d="M 32,358 Q 72,372 112,358" stroke="#FF9900" fill="none" stroke-width="3.2" stroke-linecap="round"/>
        <polygon points="111,354 119,358 111,362" fill="#FF9900"/>

        <!-- Cockpit Glass -->
        <path d="M 136,314 L 158,318 Q 178,324 182,344 L 142,344 Z" fill="#38BDF8" opacity="0.85"/>
        <line x1="144" y1="318" x2="160" y2="342" stroke="white" stroke-width="1.8" opacity="0.55"/>
        <circle cx="156" cy="334" r="4" fill="#0F172A" opacity="0.75"/>

        <!-- Headlight -->
        <circle cx="184" cy="358" r="4.5" fill="#FEF08A" stroke="#FFFFFF" stroke-width="1"/>

        <!-- Wheels -->
        <g transform="translate(36, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="#64748B"/><circle r="2.5" fill="#00A8E1"/>
        </g>
        <g transform="translate(120, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="#64748B"/><circle r="2.5" fill="#00A8E1"/>
        </g>
        <g transform="translate(162, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="#64748B"/><circle r="2.5" fill="#00A8E1"/>
        </g>
      </g>


      <!-- ══════════════════════════════════════════
           7. VEHICLE 3: FLIPKART EXPRESS VAN
           ══════════════════════════════════════════ -->
      <g style="animation: mw-fk 20s cubic-bezier(0.35,0,0.15,1) infinite; will-change: transform;">

        <!-- Headlight Beam -->
        <polygon points="185,398 290,380 290,435 185,406" fill="url(#mw-headlight)"/>

        <!-- Ground Shadow -->
        <ellipse cx="95" cy="424" rx="96" ry="5.5" fill="rgba(0,0,0,0.65)" filter="blur(3px)"/>

        <!-- Van Body -->
        <path d="M 4,402 L 4,316 Q 4,308 12,308 L 134,308 L 158,312 Q 184,318 186,346 L 188,402 Z" fill="url(#mw-fk-body)" stroke="#024E79" stroke-width="1.5"/>
        <rect x="4" y="308" width="138" height="8" fill="#FACC15"/>

        <!-- Clean Branded Panel -->
        <rect x="16" y="326" width="112" height="45" fill="#FFFFFF" rx="3" stroke="#E2E8F0" stroke-width="1"/>
        <rect x="16" y="326" width="9"  height="45" fill="#FACC15" rx="1.5"/>
        <text x="74" y="350" text-anchor="middle" font-size="16" font-weight="900" fill="#0284C7" font-family="'Outfit', sans-serif" letter-spacing="-0.5">flipkart</text>
        <text x="74" y="362" text-anchor="middle" font-size="7" font-weight="800" fill="#64748B" font-family="sans-serif">24 HR EXPRESS DELIVERY</text>

        <!-- Driver Windshield Glass -->
        <path d="M 136,314 L 158,318 Q 178,324 182,344 L 142,344 Z" fill="#38BDF8" opacity="0.85"/>
        <line x1="144" y1="318" x2="160" y2="342" stroke="white" stroke-width="1.8" opacity="0.55"/>
        <circle cx="156" cy="334" r="4" fill="#0F172A" opacity="0.75"/>

        <!-- Headlight -->
        <rect x="183" y="352" width="5" height="9" fill="#FEF08A" rx="1"/>

        <!-- Wheels -->
        <g transform="translate(36, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="url(#mw-chrome)"/><circle r="2.5" fill="#FACC15"/>
        </g>
        <g transform="translate(120, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="url(#mw-chrome)"/><circle r="2.5" fill="#FACC15"/>
        </g>
        <g transform="translate(162, 414)">
          <circle r="12" fill="#0F172A"/><circle r="9.5" fill="#1E293B"/><circle r="5" fill="url(#mw-chrome)"/><circle r="2.5" fill="#FACC15"/>
        </g>
      </g>


      <!-- ══════════════════════════════════════════
           8. CARTONS & DISPATCH BURSTS
           ══════════════════════════════════════════ -->

      <!-- Inbound Shipping Cartons -->
      <g style="animation: mw-box-in-1 20s ease-in-out infinite; will-change: transform;">
        <rect width="20" height="17" fill="url(#mw-box-1)" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="10" y1="0" x2="10" y2="17" stroke="#FBBF24" stroke-width="2.5" opacity="0.7"/>
        <rect x="3" y="2" width="7" height="4" fill="white" rx="0.5"/>
        <rect x="12" y="2" width="6" height="3" fill="#EF4444" rx="0.5"/>
      </g>
      <g style="animation: mw-box-in-2 20s ease-in-out infinite; will-change: transform;">
        <rect width="18" height="15" fill="url(#mw-box-2)" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="9" y1="0" x2="9" y2="15" stroke="#FBBF24" stroke-width="2.5" opacity="0.7"/>
        <rect x="2" y="3" width="6" height="4" fill="white" rx="0.5"/>
      </g>
      <g style="animation: mw-box-in-3 20s ease-in-out infinite; will-change: transform;">
        <rect width="22" height="18" fill="url(#mw-box-1)" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="11" y1="0" x2="11" y2="18" stroke="#92400E" stroke-width="2.5" opacity="0.6"/>
        <rect x="3" y="3" width="8" height="5" fill="white" rx="0.5"/>
        <rect x="14" y="3" width="6" height="3.5" fill="#EF4444" rx="0.5"/>
      </g>

      <!-- Outbound Amazon Parcels -->
      <g style="animation: mw-box-amz-1 20s ease-in-out infinite; will-change: transform;">
        <rect width="18" height="15" fill="#D97706" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="9" y1="0" x2="9" y2="15" stroke="#131921" stroke-width="2.8"/>
        <path d="M 4,10 Q 9,14 14,10" stroke="#131921" fill="none" stroke-width="1.4"/>
        <rect x="2" y="2" width="6" height="3.5" fill="white" rx="0.5"/>
      </g>
      <g style="animation: mw-box-amz-2 20s ease-in-out infinite; will-change: transform;">
        <rect width="16" height="14" fill="#F59E0B" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="8" y1="0" x2="8" y2="14" stroke="#131921" stroke-width="2.2"/>
        <rect x="2" y="2" width="5" height="3" fill="white" rx="0.5"/>
      </g>

      <!-- Outbound Flipkart Parcels -->
      <g style="animation: mw-box-fk-1 20s ease-in-out infinite; will-change: transform;">
        <rect width="18" height="15" fill="#0284C7" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="9" y1="0" x2="9" y2="15" stroke="#FACC15" stroke-width="2.8"/>
        <rect x="2" y="2" width="6" height="3.5" fill="white" rx="0.5"/>
      </g>
      <g style="animation: mw-box-fk-2 20s ease-in-out infinite; will-change: transform;">
        <rect width="16" height="14" fill="#F59E0B" rx="2" filter="drop-shadow(0 2px 3px rgba(0,0,0,0.4))"/>
        <line x1="8" y1="0" x2="8" y2="14" stroke="#0284C7" stroke-width="2.2"/>
        <rect x="2" y="2" width="5" height="3" fill="white" rx="0.5"/>
      </g>

      <!-- Fast Departure Bursts -->
      <g style="animation: mw-burst-amz 20s ease-in-out infinite; transform-origin: 540px 400px;">
        <ellipse cx="540" cy="400" rx="42" ry="14" fill="#FF9900" opacity="0.35" filter="blur(5px)"/>
        <polygon points="515,400 565,394 555,400 565,406" fill="#FF9900" opacity="0.75"/>
      </g>
      <g style="animation: mw-burst-fk 20s ease-in-out infinite; transform-origin: 540px 400px;">
        <ellipse cx="540" cy="400" rx="42" ry="14" fill="#0284C7" opacity="0.35" filter="blur(5px)"/>
        <polygon points="515,400 565,394 555,400 565,406" fill="#FACC15" opacity="0.75"/>
      </g>

    </g>
  </svg>
</div>
