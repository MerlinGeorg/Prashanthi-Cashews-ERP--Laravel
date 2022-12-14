<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->nationalities() as $nationality){
            $user = \App\Models\Nationality::create($nationality);            
		}
    }
	
	public function nationalities(){		
        yield [
            'name' 	=> 'Afghan',
        ];
        yield [
            'name' 	=> 'Albanian',
        ];
        yield [
            'name' 	=> 'Algerian',
        ];
        yield [
            'name' 	=> 'American',
        ];
        yield [
            'name' 	=> 'Andorran',
        ];
        yield [
            'name' 	=> 'Angolan',
        ];
        yield [
            'name' 	=> 'Anguillan',
        ];
        yield [
            'name' 	=> 'Argentine',
        ];
        yield [
            'name' 	=> 'Armenian',
        ];
        yield [
            'name' 	=> 'Australian',
        ];
        yield [
            'name' 	=> 'Austrian',
        ];
        yield [
            'name' 	=> 'Azerbaijani',
        ];
        yield [
            'name' 	=> 'Bahamian',
        ];
        yield [
            'name' 	=> 'Bahraini',
        ];
        yield [
            'name' 	=> 'Bangladeshi',
        ];
        yield [
            'name' 	=> 'Barbadian',
        ];
        yield [
            'name' 	=> 'Belarusian',
        ];
        yield [
            'name' 	=> 'Belgian',
        ];
        yield [
            'name' 	=> 'Belizean',
        ];
        yield [
            'name' 	=> 'Beninese',
        ];
        yield [
            'name' 	=> 'Bermudian',
        ];
        yield [
            'name' 	=> 'Bhutanese',
        ];
        yield [
            'name' 	=> 'Bolivian',
        ];
        yield [
            'name' 	=> 'Botswanan',
        ];
        yield [
            'name' 	=> 'Brazilian',
        ];
        yield [
            'name' 	=> 'British',
        ];
        yield [
            'name' 	=> 'British Virgin Islander',
        ];
        yield [
            'name' 	=> 'Bruneian',
        ];
        yield [
            'name' 	=> 'Bulgarian',
        ];
        yield [
            'name' 	=> 'Burkinan',
        ];
        yield [
            'name' 	=> 'Burmese',
        ];
        yield [
            'name' 	=> 'Burundian',
        ];
        yield [
            'name' 	=> 'Cambodian',
        ];
        yield [
            'name' 	=> 'Cameroonian',
        ];
        yield [
            'name' 	=> 'Canadian',
        ];
        yield [
            'name' 	=> 'Cape Verdean',
        ];
        yield [
            'name' 	=> 'Cayman Islander',
        ];
        yield [
            'name' 	=> 'Central African',
        ];
        yield [
            'name' 	=> 'Chadian',
        ];
        yield [
            'name' 	=> 'Chilean',
        ];
        yield [
            'name' 	=> 'Chinese',
        ];
        yield [
            'name' 	=> 'Citizen of Antigua and Barbuda',
        ];
        yield [
            'name' 	=> 'Citizen of Bosnia and Herzegovina',
        ];
        yield [
            'name' 	=> 'Citizen of Guinea-Bissau',
        ];
        yield [
            'name' 	=> 'Citizen of Kiribati',
        ];
        yield [
            'name' 	=> 'Citizen of Seychelles',
        ];
        yield [
            'name' 	=> 'Citizen of the Dominican Republic',
        ];
        yield [
            'name' 	=> 'Citizen of Vanuatu',
        ];
        yield [
            'name' 	=> 'Colombian',
        ];
        yield [
            'name' 	=> 'Comoran',
        ];
        yield [
            'name' 	=> 'Congolese (Congo)',
        ];
        yield [
            'name' 	=> 'Congolese (DRC)',
        ];
        yield [
            'name' 	=> 'Cook Islander',
        ];
        yield [
            'name' 	=> 'Costa Rican',
        ];
        yield [
            'name' 	=> 'Croatian',
        ];
        yield [
            'name' 	=> 'Cuban',
        ];
        yield [
            'name' 	=> 'Cymraes',
        ];
        yield [
            'name' 	=> 'Cymro',
        ];
        yield [
            'name' 	=> 'Cypriot',
        ];
        yield [
            'name' 	=> 'Czech',
        ];
        yield [
            'name' 	=> 'Danish',
        ];
        yield [
            'name' 	=> 'Djiboutian',
        ];
        yield [
            'name' 	=> 'Dominican',
        ];
        yield [
            'name' 	=> 'Dutch',
        ];
        yield [
            'name' 	=> 'East Timorese',
        ];
        yield [
            'name' 	=> 'Ecuadorean',
        ];
        yield [
            'name' 	=> 'Egyptian',
        ];
        yield [
            'name' 	=> 'Emirati',
        ];
        yield [
            'name' 	=> 'English',
        ];
        yield [
            'name' 	=> 'Equatorial Guinean',
        ];
        yield [
            'name' 	=> 'Eritrean',
        ];
        yield [
            'name' 	=> 'Estonian',
        ];
        yield [
            'name' 	=> 'Ethiopian',
        ];
        yield [
            'name' 	=> 'Faroese',
        ];
        yield [
            'name' 	=> 'Fijian',
        ];
        yield [
            'name' 	=> 'Filipino',
        ];
        yield [
            'name' 	=> 'Finnish',
        ];
        yield [
            'name' 	=> 'French',
        ];
        yield [
            'name' 	=> 'Gabonese',
        ];
        yield [
            'name' 	=> 'Gambian',
        ];
        yield [
            'name' 	=> 'Georgian',
        ];
        yield [
            'name' 	=> 'German',
        ];
        yield [
            'name' 	=> 'Ghanaian',
        ];
        yield [
            'name' 	=> 'Gibraltarian',
        ];
        yield [
            'name' 	=> 'Greek',
        ];
        yield [
            'name' 	=> 'Greenlandic',
        ];
        yield [
            'name' 	=> 'Grenadian',
        ];
        yield [
            'name' 	=> 'Guamanian',
        ];
        yield [
            'name' 	=> 'Guatemalan',
        ];
        yield [
            'name' 	=> 'Guinean',
        ];
        yield [
            'name' 	=> 'Guyanese',
        ];
        yield [
            'name' 	=> 'Haitian',
        ];
        yield [
            'name' 	=> 'Honduran',
        ];
        yield [
            'name' 	=> 'Hong Konger',
        ];
        yield [
            'name' 	=> 'Hungarian',
        ];
        yield [
            'name' 	=> 'Icelandic',
        ];
        yield [
            'name' 	=> 'Indian',
        ];
        yield [
            'name' 	=> 'Indonesian',
        ];
        yield [
            'name' 	=> 'Iranian',
        ];
        yield [
            'name' 	=> 'Iraqi',
        ];
        yield [
            'name' 	=> 'Irish',
        ];
        yield [
            'name' 	=> 'Israeli',
        ];
        yield [
            'name' 	=> 'Italian',
        ];
        yield [
            'name' 	=> 'Ivorian',
        ];
        yield [
            'name' 	=> 'Jamaican',
        ];
        yield [
            'name' 	=> 'Japanese',
        ];
        yield [
            'name' 	=> 'Jordanian',
        ];
        yield [
            'name' 	=> 'Kazakh',
        ];
        yield [
            'name' 	=> 'Kenyan',
        ];
        yield [
            'name' 	=> 'Kittitian',
        ];
        yield [
            'name' 	=> 'Kosovan',
        ];
        yield [
            'name' 	=> 'Kuwaiti',
        ];
        yield [
            'name' 	=> 'Kyrgyz',
        ];
        yield [
            'name' 	=> 'Lao',
        ];
        yield [
            'name' 	=> 'Latvian',
        ];
        yield [
            'name' 	=> 'Lebanese',
        ];
        yield [
            'name' 	=> 'Liberian',
        ];
        yield [
            'name' 	=> 'Libyan',
        ];
        yield [
            'name' 	=> 'Liechtenstein citizen',
        ];
        yield [
            'name' 	=> 'Lithuanian',
        ];
        yield [
            'name' 	=> 'Luxembourger',
        ];
        yield [
            'name' 	=> 'Macanese',
        ];
        yield [
            'name' 	=> 'Macedonian',
        ];
        yield [
            'name' 	=> 'Malagasy',
        ];
        yield [
            'name' 	=> 'Malawian',
        ];
        yield [
            'name' 	=> 'Malaysian',
        ];
        yield [
            'name' 	=> 'Maldivian',
        ];
        yield [
            'name' 	=> 'Malian',
        ];
        yield [
            'name' 	=> 'Maltese',
        ];
        yield [
            'name' 	=> 'Marshallese',
        ];
        yield [
            'name' 	=> 'Martiniquais',
        ];
        yield [
            'name' 	=> 'Mauritanian',
        ];
        yield [
            'name' 	=> 'Mauritian',
        ];
        yield [
            'name' 	=> 'Mexican',
        ];
        yield [
            'name' 	=> 'Micronesian',
        ];
        yield [
            'name' 	=> 'Moldovan',
        ];
        yield [
            'name' 	=> 'Monegasque',
        ];
        yield [
            'name' 	=> 'Mongolian',
        ];
        yield [
            'name' 	=> 'Montenegrin',
        ];
        yield [
            'name' 	=> 'Montserratian',
        ];
        yield [
            'name' 	=> 'Moroccan',
        ];
        yield [
            'name' 	=> 'Mosotho',
        ];
        yield [
            'name' 	=> 'Mozambican',
        ];
        yield [
            'name' 	=> 'Namibian',
        ];
        yield [
            'name' 	=> 'Nauruan',
        ];
        yield [
            'name' 	=> 'Nepalese',
        ];
        yield [
            'name' 	=> 'New Zealander',
        ];
        yield [
            'name' 	=> 'Nicaraguan',
        ];
        yield [
            'name' 	=> 'Nigerian',
        ];
        yield [
            'name' 	=> 'Nigerien',
        ];
        yield [
            'name' 	=> 'Niuean',
        ];
        yield [
            'name' 	=> 'North Korean',
        ];
        yield [
            'name' 	=> 'Northern Irish',
        ];
        yield [
            'name' 	=> 'Norwegian',
        ];
        yield [
            'name' 	=> 'Omani',
        ];
        yield [
            'name' 	=> 'Pakistani',
        ];
        yield [
            'name' 	=> 'Palauan',
        ];
        yield [
            'name' 	=> 'Palestinian',
        ];
        yield [
            'name' 	=> 'Panamanian',
        ];
        yield [
            'name' 	=> 'Papua New Guinean',
        ];
        yield [
            'name' 	=> 'Paraguayan',
        ];
        yield [
            'name' 	=> 'Peruvian',
        ];
        yield [
            'name' 	=> 'Pitcairn Islander',
        ];
        yield [
            'name' 	=> 'Polish',
        ];
        yield [
            'name' 	=> 'Portuguese',
        ];
        yield [
            'name' 	=> 'Prydeinig',
        ];
        yield [
            'name' 	=> 'Puerto Rican',
        ];
        yield [
            'name' 	=> 'Qatari',
        ];
        yield [
            'name' 	=> 'Romanian',
        ];
        yield [
            'name' 	=> 'Russian',
        ];
        yield [
            'name' 	=> 'Rwandan',
        ];
        yield [
            'name' 	=> 'Salvadorean',
        ];
        yield [
            'name' 	=> 'Sammarinese',
        ];
        yield [
            'name' 	=> 'Samoan',
        ];
        yield [
            'name' 	=> 'Sao Tomean',
        ];
        yield [
            'name' 	=> 'Saudi Arabian',
        ];
        yield [
            'name' 	=> 'Scottish',
        ];
        yield [
            'name' 	=> 'Senegalese',
        ];
        yield [
            'name' 	=> 'Serbian',
        ];
        yield [
            'name' 	=> 'Sierra Leonean',
        ];
        yield [
            'name' 	=> 'Singaporean',
        ];
        yield [
            'name' 	=> 'Slovak',
        ];
        yield [
            'name' 	=> 'Slovenian',
        ];
        yield [
            'name' 	=> 'Solomon Islander',
        ];
        yield [
            'name' 	=> 'Somali',
        ];
        yield [
            'name' 	=> 'South African',
        ];
        yield [
            'name' 	=> 'South Korean',
        ];
        yield [
            'name' 	=> 'South Sudanese',
        ];
        yield [
            'name' 	=> 'Spanish',
        ];
        yield [
            'name' 	=> 'Sri Lankan',
        ];
        yield [
            'name' 	=> 'St Helenian',
        ];
        yield [
            'name' 	=> 'St Lucian',
        ];
        yield [
            'name' 	=> 'Stateless',
        ];
        yield [
            'name' 	=> 'Sudanese',
        ];
        yield [
            'name' 	=> 'Surinamese',
        ];
        yield [
            'name' 	=> 'Swazi',
        ];
        yield [
            'name' 	=> 'Swedish',
        ];
        yield [
            'name' 	=> 'Swiss',
        ];
        yield [
            'name' 	=> 'Syrian',
        ];
        yield [
            'name' 	=> 'Taiwanese',
        ];
        yield [
            'name' 	=> 'Tajik',
        ];
        yield [
            'name' 	=> 'Tanzanian',
        ];
        yield [
            'name' 	=> 'Thai',
        ];
        yield [
            'name' 	=> 'Togolese',
        ];
        yield [
            'name' 	=> 'Tongan',
        ];
        yield [
            'name' 	=> 'Trinidadian',
        ];
        yield [
            'name' 	=> 'Tristanian',
        ];
        yield [
            'name' 	=> 'Tunisian',
        ];
        yield [
            'name' 	=> 'Turkish',
        ];
        yield [
            'name' 	=> 'Turkmen',
        ];
        yield [
            'name' 	=> 'Turks and Caicos Islander',
        ];
        yield [
            'name' 	=> 'Tuvaluan',
        ];
        yield [
            'name' 	=> 'Ugandan',
        ];
        yield [
            'name' 	=> 'Ukrainian',
        ];
        yield [
            'name' 	=> 'Uruguayan',
        ];
        yield [
            'name' 	=> 'Uzbek',
        ];
        yield [
            'name' 	=> 'Vatican citizen',
        ];
        yield [
            'name' 	=> 'Venezuelan',
        ];
        yield [
            'name' 	=> 'Vietnamese',
        ];
        yield [
            'name' 	=> 'Vincentian',
        ];
        yield [
            'name' 	=> 'Wallisian',
        ];
        yield [
            'name' 	=> 'Welsh',
        ];
        yield [
            'name' 	=> 'Yemeni',
        ];
        yield [
            'name' 	=> 'Zambian',
        ];
        yield [
            'name' 	=> 'Zimbabwean',
        ];
    }

}