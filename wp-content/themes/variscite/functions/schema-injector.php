<?php
add_action('wp_footer', 'schemaorg_structure_injector');
function schemaorg_structure_injector() {


    // SINGLE POST
    if (is_singular('post')) {
      $include_post_author = get_field('include_post_author', get_the_ID());
      $p['id'] = get_the_ID();
      $p['title'] = get_the_title();
      $p['excerpt'] = get_the_excerpt();
      $p['thumb'] = get_the_post_thumbnail_url($p['id'], 'full');
      $p['date'] = get_the_date('c', $p['id']);
      $p['author'] = $include_post_author ? get_the_author() : 'Variscite';
      $p['org'] = get_field('optage_company_sname', 'option');
      $p['logo'] = get_field('optage_company_logo', 'option');
  
      $schema_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'author' => $p['author'],
        'name' => $p['title'],
        'datePublished' => $p['date'],
        'articleBody' => $p['excerpt'],
        'publisher' => array(
          '@type' => 'Organization',
          'name' => $p['org'],
          'logo' => $p['logo']
        )
      );
      echo '<script type="application/ld+json">' . json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  
    }

    elseif(is_singular('specs')) {

        $spec['id']       = get_the_ID();
        $spec['category'] = get_the_terms($spec['id'], 'products');

        $som_term_id = get_term_by('slug', 'system-on-module-som', 'products')->term_id;
        $is_som = ($spec['category'][0]->parent == $som_term_id) || ($spec['category'][0]->term_id == $som_term_id);

		$product_thumbs	= get_field('vrs_specs_slider_media', $spec['id']);
        $spec['thumbs'] = '';

		foreach($product_thumbs as $thumb_key => $product_thumb) {
            $spec['thumbs'] .= '"' . $product_thumb['sliderimg'] . '"';

            if($thumb_key + 1 < count($product_thumbs)) {
                $spec['thumbs'] .= ', ' . "\n";
            } else {
                $spec['thumbs'] .= "\n";
            }
        }

        $spec['title']    = get_the_title() . ($is_som ? ' System on Module' : '');
        $spec['desc']	  = strip_tags(get_field('vrs_specs_product_middesc', $spec['id']));
        $spec['sku']      = trim(explode(':', $spec['title'])[0]);
        $spec['url']      = get_permalink($spec['id']);
        $spec['price']    = trim(str_replace(
            array('starting', 'from', ':', 'price', '$'),
            '',
            strtolower(get_field('vrs_specs_price', $spec['id']))
        ));

        if(
            ctype_alnum(str_replace(' ', '', $spec['price'])) ||
            ctype_alpha(str_replace(' ', '', $spec['price']))
        ) {
            $spec['price'] = preg_replace("/[^0-9.]/", "", $spec['price']);
        }

        if(empty($spec['price'])) {
            $spec['price'] = 0;
        }

        echo preg_replace( "/\r|\n|\s+/", " ",'
        <script type="application/ld+json">
        {
            "@context": "https://schema.org/",
            "@type": "Product",
            "name": "' . $spec['title'] . '",
            "gtin8": "' . $spec['title'] . '",
            "image": [
                ' . $spec['thumbs'] . '
            ],
            "description": "' . $spec['desc'] . '",
            "brand": {
              "@type": "https://schema.org/Brand",
              "name": "Variscite"
        },
            "sku": "' . $spec['sku'] . '",
        "offers": {
                "@type": "Offer",
                "url": "' . $spec['url'] . '",
                "priceCurrency": "USD",
                "price": "' . $spec['price'] . '",
                "itemCondition": "https://schema.org/NewCondition",
                "availability": "https://schema.org/InStock",
                "seller": {
                    "@type": "Organization",
                    "name": "Variscite"
                }
            }
        }
        </script>
        ') . "\n" . "\n";
    } elseif (is_page( 'support' )) {
        echo '
        <script type="application/ld+json">
        {        
          "@context": "https://schema.org",        
          "@type": "FAQPage",        
          "mainEntity": [{        
            "@type": "Question",        
            "name": "Does Variscite provide free hardware and software design support?",        
            "acceptedAnswer": {        
              "@type": "Answer",        
              "text": "Variscite provides customers with a complete hardware and software reference platform for its System-on-Module solutions. The related reference carrier board design files can be freely used by the customer to develop their own carrier board. Software drivers and BSPs of the evaluation kits are also provided free of charge. Details are available at Variscite Wiki."        
            }        
          },
          {        
            "@type": "Question",        
            "name": "Can Variscite provide hardware and software design services for customer specific end product requirements?",        
            "acceptedAnswer": {        
              "@type": "Answer",        
              "text": "When a particular hardware design is required for a customer’s custom carrier board or a specific driver development/integration for an end product, Variscite can provide paid support services, implemented by an expert team. For further details, please contact us at sales@variscite.com."
            }        
          },
          {        
            "@type": "Question",        
            "name": "How can I get access to the carrier design files and software source codes?",        
            "acceptedAnswer": {        
              "@type": "Answer",        
              "text": "Most documents pertaining to hardware can be found in the documentation section of each product page. This includes the complete SoM datasheet and its carrier board, 2D and 3D mechanical files, carrier’s schematics in PDF form, radiation tests and more. Information regarding software can be found at Variscite Wiki. Carrier’s OrCAD schematics, layout files and software source codes are available on Variscite’s FTP. Ask for login details from your account manager."
            }        
          },
          {        
            "@type": "Question",        
            "name": "If I have a technical issue at the evaluation stage or while developing my end product around Variscite’s SoM, how can I get technical support?",        
            "acceptedAnswer": {        
              "@type": "Answer",        
              "text": "Most technical details concerning the evaluation or development around Variscite’s solutions can be found at Variscite Wiki. For any further technical questions, a customer can submit a ticket at the customer portal which will be promptly supported by Variscite’s engineers."
            }        
          },
          {        
            "@type": "Question",        
            "name": "What is the longevity commitment on Variscite products?",        
            "acceptedAnswer": {        
              "@type": "Answer",        
              "text": "Variscite provides a very generous longevity commitment, declaring Variscite will continue producing its products as long as the critical components in the design are supplied by the relevant vendors. In addition, Variscite makes every effort to use leading chip vendors for its products to ensure at least 10 years longevity for products - based on the commitment provided by the chip vendors.Visit our product longevity page."
            }        
          }]        
        }
        </script>        
        ';
    } elseif (is_page( 'training' )) {
      echo '
      <script type="application/ld+json">
      {
        "@context": "https://schema.org",
          "@graph": 
            [
              {
                  "@type": "VideoObject",
                  "name": "Webinar: How to bring SECURE products to market quickly with Sequitur Labs and Variscite?",
                  "description": "If you know that security is a critical need for any smart device – and you need a solution that’s robust and easy to implement - don’t miss this webinar.
                
                Billions of devices are expected to be online in the next few years, but about half of IoT vendors have experienced a security breach at least once. Compromised security can hurt your brand and result in lost revenues and customer trust.  This problem is getting more serious with the deployment of AI models at the network edge.
                
                Edge device developers need to be assured that their products are designed, manufactured and deployed without risk of being compromised.
                
                Implementing IoT security is, however, a big challenge. It requires understanding a variety of new features and functions and investment in implementing security in a diverse, fragmented microprocessor market. As a result, IoT device vendors need a partner who can deliver a solution that allows them to easily implement security features and functions, and focus their investment and energy on delivering great IoT products and solutions. 
                
                The combination of Variscite’s capability to consistently deliver high-quality hardware and software solutions with Sequitur’s complete chip-to-cloud solution solves the complex security problems for edge devices.
                
                
                This webinar will cover:
                
                1. Variscite’s introduction
                2. About the i.MX 8M Plus platform 
                3. Device Security basics: ARM Trustzone, Secure Enclaves, Secure Boot, Failure Recovery and Secure Product Updates
                4. Chip-to-Cloud integration: mutual authentication and secure device registration
                5. Cloud-supported device updates, management and protection
                6. Methods for protecting intellectual property – in particular, AI models – at the network edge
                7. Implementing robust security with the Sequitur Labs’ EmSPARK™ Security Suite and Variscite i.MX 8M Plus System on Modules",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/10/sequitur-labs-youtube_bar.png",
                  "uploadDate": "2021-10-17",
                  "duration": "PT56M54S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=ldJbXlAHO80",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-how-to-bring-secure-products-to-market-quickly-with-sequitur-labs-and-variscite/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-how-to-bring-secure-products-to-market-quickly-with-sequitur-labs-and-variscite/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Getting Started Debugging C/C++ on Variscite System on Modules",
                  "description": "This webinar will cover the basics for debugging Linux C/C++ projects on Variscite System on Modules. 

                      In this webinar, a Variscite expert will demonstrate how to create, compile, and debug projects using Visual Studio Code.

                      You will learn how to:

                      Set up a new development host computer
                      Create and cross-compile a new project
                      Debug applications using VS Code’s graphical debugger
                      Enable your team to develop and debug applications from remote Linux/Windows/Mac OS X development computers",
                        "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/09/Getting-Started-Debugging-CC-on-Variscite-System-on-Modules_youtube_bar.png",
                        "uploadDate": "2021-09-30",
                        "duration": "PT47M50S",  
                        "publisher": {
                          "@type": "Organization",
                          "name": "Variscite",
                          "logo": {
                            "@type": "ImageObject",
                            "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                            "width": 175,
                            "height": 33
                          }
                        },
                        "contentUrl": "https://www.youtube.com/watch?v=xi-ZWY183QI",
                        "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-debugging-c-c-on-variscite-system-on-modules/",
                        "potentialAction": {
                          "@type": "SeekToAction",
                          "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-debugging-c-c-on-variscite-system-on-modules/={seek_to_second_number}",
                          "startOffset-input": "required name=seek_to_second_number"
                        }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Getting started with Basler Embedded Vision Solutions for Variscite’s i.MX 8M Plus SoMs",
                  "description": "This webinar will focus on the complete solution for the embedded market combining Variscite’s iMX 8M Plus System on Modules and Basler’s vision solutions. 

                          From rapid prototyping to series production – learn how you can quickly realize your embedded vision system.

                          This webinar will cover:

                          Industrial machine vision trends & developments
                          Basler’s embedded vision solutions
                          Full system integration of Basler’s camera sensors with Variscite’s i.MX 8M Plus System on Module:
                                          -Hardware aspects

                                          -Software aspects

                                          -Overview of the complete evaluation kit 

                          The support model: How Basler and Variscite take care of your complete embedded vision system",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/07/Getting-started-with-Basler_youtube_bar.png",
                  "uploadDate": "2021-07-04",
                  "duration": "PT32M59S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=DR8z8l97khw",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-with-basler-embedded-vision-solutions-for-variscites-i-mx-8m-plus-soms/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-with-basler-embedded-vision-solutions-for-variscites-i-mx-8m-plus-soms/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Getting Started with Device Trees on Variscite SoMs​",
                  "description": "The webinar will cover the basics of Linux Device Tree development for Variscite System on Modules. 

                            You will learn: 

                            An introduction to Device Tree fundamentals and driver integration
                            How to configure pin functions and settings
                            How to add new device nodes
                            Practical debugging tips
                            We will also demonstrate how to create a new Device Tree and integrate it into the Yocto build system.",
                    "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/06/device_tree_free_youtube.png",
                    "uploadDate": "2021-06-14",
                    "duration": "PT42M11S",  
                    "publisher": {
                      "@type": "Organization",
                      "name": "Variscite",
                      "logo": {
                        "@type": "ImageObject",
                        "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                        "width": 175,
                        "height": 33
                      }
                      },
                      "contentUrl": "https://www.youtube.com/watch?v=nTyO2_D-NUk",
                      "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-with-device-tree-on-variscite-soms/",
                      "potentialAction": {
                        "@type": "SeekToAction",
                        "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-getting-started-with-device-tree-on-variscite-soms/={seek_to_second_number}",
                        "startOffset-input": "required name=seek_to_second_number"
                      }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Building Your First Application With QML Technology: The Backend (Qt Webinar Part 3)",
                  "description": "This webinar will focus on the backend of a QML application. How to link the graphical elements to the HW backend and dynamically control them. Variscite’s experts will demonstrate the theoretical info and provide practical tips.

                      In this webinar, you will learn:

                      Qt Quick summary
                      Qt framework and CAN bus support
                      Analysis of a QML application
                      QML / C++ integration
                      Sample code
                      Back to the Car Dashboard example: The Engine Control Unit
                      The Car Dashboard example: Bringing it all together",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/05/QML-The_backend_youtube.png",
                  "uploadDate": "2021-05-09",
                  "duration": "PT45M9S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=Ts7nTSDYgeI",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-building-your-first-application-with-qml-technology-the-backend/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-building-your-first-application-with-qml-technology-the-backend/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Variscite’s i.MX 8M Plus Leading System-on-Module Solutions",
                  "description": "The webinar will present the new generation of System on Modules with integrated Artificial Intelligence (AI) / Machine Learning (ML) capabilities based on NXP’s i.MX 8M Plus processor.

                        This webinar will cover VAR-SOM-MX8M-PLUS and DART-MX8M-PLUS special features, software aspects, and hardware interfaces compatibility, which should be considered in the design phase.

                        In this webinar, you will learn:

                        1. An overview of the new i.MX 8M Plus System on Modules
                        2. How to build your design based on DART-MX8M-PLUS and VAR-SOM-MX8M-PLUS
                        3. Migration among compatible modules and Variscite’s Pin2Pin families
                        4. Getting started with software development for Variscite’s new modules",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/04/Variscites-i.MX-8M-Plus-Leading-System-on-Module-Solutions_youtube.png",
                  "uploadDate": "2021-04-13",
                  "duration": "PT59M22S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=lis032mCP3M",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-variscites-i-mx-8m-plus-leading-system-on-module-solutions/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-variscites-i-mx-8m-plus-leading-system-on-module-solutions/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar: Building Your First Application With QML Technology: The Frontend (Qt Webinar Part 2)",
                  "description": "This webinar will focus on the graphical aspects of Qt Quick development, aiming to share the knowledge-base of QML programming.

                      This webinar will cover:

                      Qt Quick brief summary
                      Basic concept: the rectangle
                      Basic concept: the anchors
                      Basic concept: the components
                      Designing a car dashboard
                      Dashboard: identify the graphical elements
                      Dashboard: the canvas

                      On the next webinar, we will cover the HW integration and C/C++ backend (Qt Webinar Part 3).",
                      "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2021/03/Building-Your-First-Application-With-QML-Technology_youtube.png",
                      "uploadDate": "2021-03-01",
                      "duration": "PT41M24S",  
                      "publisher": {
                          "@type": "Organization",
                          "name": "Variscite",
                          "logo": {
                          "@type": "ImageObject",
                          "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                          "width": 175,
                          "height": 33
                          }
                      },
                  "contentUrl": "https://www.youtube.com/watch?v=du6fVR2v41U",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-building-your-first-application-with-qml-technology/",
                  "potentialAction": {
                      "@type": "SeekToAction",
                      "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/webinar-building-your-first-application-with-qml-technology/={seek_to_second_number}",
                      "startOffset-input": "required name=seek_to_second_number"
                }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar : Machine Learning Embedded on Variscite’s i.MX 8M Plus System-on-Module",
                  "description": "This webinar will present Variscite’s newest embedded System-on-Module (SoM), based on the i.MX 8M Plus applications processor from NXP Semiconductors. 



                        The webinar will cover: 

                        An overview of NXP’s i.MX 8M applications processor family, featuring special capabilities of the recently-added i.MX 8M Plus 
                        An intro to the i.MX 8M Plus neural processing unit (NPU) for machine learning 
                        Variscite’s Pin2Pin families 
                        Variscite’s i.MX 8M Plus SoM offering – hardware, software, and complete evaluation kits
                        VAR-SOM-MX6 Course
                        Other services
                        Training
                        Software Development
                        Manufacturing
                        Products
                        System on Module
                        Pin2Pin families
                        Single Board Computers
                        Evaluation Kits
                        Accessories
                        Services
                        Board Design Services
                        Software Development Services
                        Manufacturing Services
                        Variscite Training
                        Latest SoM
                        VAR-SOM-MX8M-NANO
                        VAR-SOM-MX8M-PLUS
                        DART-MX8M-PLUS
                        VAR-SOM-MX8M-MINI
                        DART-MX8M-MINI
                        VAR-SOM-MX8
                        VAR-SOM-MX8X
                        Processors",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2020/12/webinar_Variscite_nxp_youtube_bar-300x189.png",
                  "uploadDate": "2020-12-08",
                  "duration": "PT38M40S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=-9VogbIIy0c",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/machine-learning-embedded-on-variscites-i-mx-8m-plus-system-on-module/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/machine-learning-embedded-on-variscites-i-mx-8m-plus-system-on-module/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar : Getting Started with Qt Framework on Your Embedded Board (Qt Webinar Part 1)",
                  "description": "The webinar will cover the basics of Qt and will provide answers to questions like:
                
                What is Qt?
                Is Qt limited to graphics only?
                What are the main programming languages supported?
                What is Qt Creator?
                How do I get started with the Qt application framework on my embedded board?
                The theoretical information will be followed by a demo as well as practical tips & tricks from Variscite’s experts.
                
                This webinar’s content will lay the ground for our next Qt webinar, where you will learn how to build a dashboard application using QML technology.",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2020/09/Getting-Started-with-Qt-Framework_youtube_bar.png",
                  "uploadDate": "2020-09-22",
                  "duration": "PT46M57S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=4i-DWio3tlc",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/getting-started-with-qt-framework-on-your-embedded-board/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/getting-started-with-qt-framework-on-your-embedded-board/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  } 
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar : Robust and Secure Over-The-Air (OTA) Software Updates with Mender",
                  "description": "Variscite and Mender’s joint webinar.

                      Learn why and how to use Over-The-Air (OTA) Software Updates and gain the ability to efficiently deliver remote software updates at scale, while using industry best practices in security and robustness.  

                      In this joint webinar we will cover the following topics:

                      - Why System on Module? 
                      - Why Mender and Variscite partnership!
                      - Introduction to a secure and robust OTA software update process with Mender, an open-source end-to-end update manager for embedded devices. 
                      - Key challenges in developing OTA solutions 
                      - Considerations for being successful with software updates to connected devices. 
                      - Discuss the aspects of integrating Mender into your embedded application 
                      - Briefly discuss advanced features of Mender enabling updates at large scale with risk management and automated deployment capabilities 
                      - Show a product demo with one of Variscite’s i.MX8M based development platforms.",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2020/07/Robust-and-Secure-Over-The-Air-OTA-Software-Updates-with-Mender.png",
                  "uploadDate": "2020-07-27",
                  "duration": "PT43M16S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=pmg0sAl6sGY",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/robust-and-secure-over-the-air-ota-software-updates-with-mender/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/robust-and-secure-over-the-air-ota-software-updates-with-mender/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar : How to design a scalable embedded product supporting different NXP i.MX applications processor families",
                  "description": "NXP and Variscite’s joint webinar.

                        Embedded processors are constantly evolving, with more integrated features, better performance and a wider set of interfaces. Designing your next-generation embedded product becomes a complex challenge both on software and hardware aspects.  

                        Leveraging the full span of NXP’s i.MX families with Variscite’s System-on-Module solutions ease your path to a successful embedded product launch while maintaining full scalability between the different i.MX processors and extending your product lifetime.

                        In this webinar you will learn about:

                        NXP’s i.MX product portfolio with a focus on the new i.MX 8 families
                        The System-on-Module concept and how to leverage it to shorten time to market and optimize your R&D resources
                        Variscite pin-to-pin System-on-Module families as the basis for a complete scalable embedded product design
                        How to design a scalable embedded product supporting various i.MX processors: 
                        - i.MX 6ULL, through i.MX 6
                        -i.MX 8M NANO, i.MX 8M MINI, i.MX 8M PLUS, i.MX 8X and i.MX 8QuadMax
                        A scalable design: Hardware aspects 
                        A scalable design: Software aspects",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2020/07/NXP-and-Variscites-joint-webinar_youtube_bar.png",
                  "uploadDate": "2020-06-29",
                  "duration": "PT61M12S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=CNGpdf_JPuE",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/how-to-design-a-scalable-embedded-product/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/how-to-design-a-scalable-embedded-product/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              },
              {
                  "@type": "VideoObject",
                  "name": "Webinar : Using the Yocto Build System",
                  "description": "This webinar is an introduction to the Yocto build system and a step by step guide on how to build a Linux distribution using Yocto.

                      Webinar outline:

                      Available BSPs
                      What is Yocto
                      How Yocto builds images
                      How to customize Yocto images
                      How to build Yocto SDK

                      Learning outcome:

                      The attendee will have a basic understanding of the Yocto build system, why and how to use the system while focusing on Variscite’s DART-MX8M-MINI evaluation kit. The kit is based on the DART-MX8M-MINI System on Module powered by NXP’s i.MX 8M Mini processor.",
                  "thumbnailUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2020/07/Build-with-Yocto_youtube_bar.png",
                  "uploadDate": "2020-05-27",
                  "duration": "PT59M31S",  
                  "publisher": {
                    "@type": "Organization",
                    "name": "Variscite",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "https://wordpress-689526-3817782.cloudwaysapps.com/wp-content/uploads/2018/09/Logo-Variscite.png",
                      "width": 175,
                      "height": 33
                    }
                  },
                  "contentUrl": "https://www.youtube.com/watch?v=ajOPTE1Oew0",
                  "embedUrl": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/build-with-yocto/",
                  "potentialAction": {
                    "@type": "SeekToAction",
                    "target": "https://wordpress-689526-3817782.cloudwaysapps.com/services/training/build-with-yocto/={seek_to_second_number}",
                    "startOffset-input": "required name=seek_to_second_number"
                  }
              }
            ]
      }
      </script>       
      ';
  } 
}
?>