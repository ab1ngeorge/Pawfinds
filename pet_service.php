<?php
session_start();
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            background-image: url('https://source.unsplash.com/1600x900/?pet,dog,cat');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: rgba(255, 102, 0, 0.9);
            color: white;
            padding: 30px 0;
            text-align: center;
            backdrop-filter: blur(5px);
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin: 0;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }

        .service {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .service:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .service h2 {
            font-size: 1.8rem;
            color: #3498db;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .service p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }

        .service .cta {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: inline-block;
        }

        .cta:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            background-color: rgba(51, 51, 51, 0.9);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
            backdrop-filter: blur(5px);
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            .service h2 {
                font-size: 1.5rem;
            }

            .service p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <h1>Pawfind - Pet Care Services</h1>
        </div>
    </header>

    <div class="container">
        <div class="services-grid">
            <section class="service" aria-labelledby="grooming-title">
                <h2 id="grooming-title">Pet Grooming Service</h2>
                <p>Keep your pet looking and feeling their best with professional grooming services tailored to your cat or dog.</p>
                <a href="Grooming.php" class="cta" aria-label="Book Pet Grooming">Book Pet Grooming →</a>
            </section>

            <section class="service" aria-labelledby="boarding-title">
                <h2 id="boarding-title">Pet Boarding Service</h2>
                <p>When you're away, trust us to care for your furry friend. Our boarding service offers comfort, safety, and attention your pet deserves.</p>
                <a href="PetBoardingService.php" class="cta" aria-label="Book Cat and Dog Boarding Service">Book Cat and Dog Boarding Service →</a>
            </section>

            <section class="service" aria-labelledby="walking-title">
                <h2 id="walking-title">Dog Walking Service</h2>
                <p>Give your dog the exercise and fresh air they need with a personalized walking service.</p>
                <a href="PetWalkingService.php" class="cta" aria-label="Book Dog Walking">Book Dog Walking →</a>
            </section>

            <section class="service" aria-labelledby="vet-title">
                <h2 id="vet-title">Online Vet Consultation</h2>
                <p>Access professional veterinary advice from the comfort of your home, with online or in-person consultations.</p>
                <a href="petvet.php" class="cta" aria-label="Book Online Vet Consultation">Book Online Vet Consultation →</a>
            </section>

            <section class="service" aria-labelledby="training-title">
                <h2 id="training-title">Dog Training Service</h2>
                <p>Get your dog trained professionally at home with customized training sessions to improve behavior and obedience.</p>
                <a href="DogTraining.php" class="cta" aria-label="Book Dog Training Service">Book Dog Training Service →</a>
            </section>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 PawFind. All rights reserved.</p>
    </footer>

</body>
</html>