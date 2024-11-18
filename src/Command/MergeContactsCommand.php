<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use App\Entity\Contact;
use App\Services\ArrayUtils;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use App\Repository\ContactRepository;


class MergeContactsCommand extends Command
{
    protected static $defaultName = 'app:merge-contacts';
    protected static $defaultDescription = 'Checks for contacts with the same name and provides the possibility to merge';

    private $entityManager;
    private $arrayUtils;
    private $contactRepository;
    

    public function __construct(EntityManagerInterface $entityManager, ArrayUtils $arrayUtils, ContactRepository $contactRepository)
    {
        $this->entityManager = $entityManager;
        $this->arrayUtils = $arrayUtils;
        $this->contactRepository = $contactRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $contactRepository = $this->entityManager->getRepository(Contact::class);
        
        $contactCountWithSameName = $contactRepository->countByTheSameName();        
        
        foreach($contactCountWithSameName as $contact)
        {
            $output->writeln(sprintf('There are %s contacts with the name "%s":', $contact['nameCount'],$contact['name']));
            $question = new ConfirmationQuestion('Do you want to merge these users? (yes/no) ', false);

            // If answer is yes
            if ($this->getHelper('question')->ask($input, $output, $question)) {

                // Find the contacts with the same name
                $contactsWithSameName = $contactRepository->findByTheSameName($contact['name']);
                $phones = $contactRepository->findPhonesByTheSameName($contact['name']);
                

                $mergedPhones = array_reduce($phones, function ($result, $current) {
                    if (is_array($current['phones'])) {
                        $result = array_merge($result, $current['phones']);
                    }
                    return $result;
                }, []);

                //Check if there are differences in email and birthday
                $differences = $this->arrayUtils->getDifferencesBetweenArrays($contactsWithSameName);
                $data = [];

                foreach($differences as $field)
                {
                    if($field !== 'id')
                    {
                        $fieldValues = array_column($contactsWithSameName, $field);
                    
                        $question = new ChoiceQuestion(
                            'Select which value to keep, from this field: '.$field.'',
                            $fieldValues,
                            0
                        );
                        $question->setErrorMessage('Invalid choice.');
    
                        $fieldValue = $this->getHelper('question')->ask($input, $output, $question);
                        $output->writeln('You have just selected: '.$fieldValue);
    
                        $data[$field] = $fieldValue;
                    }

                }
                
                // Takes the data that is the same on both contacts and populates Data value with it.
                $firstOccurrenceOfContact = $contactsWithSameName[0];
                
                foreach($firstOccurrenceOfContact as $key => $value)
                {
                    if(!array_key_exists($key, $data))
                    
                        {
                            $data[$key] = $value;
                        }
                }
                
                // In case of birthday, it's necessary to convert to a DateTime format
                if($data['birthday']) { $data['birthday'] = DateTime::createFromFormat('Y-m-d', $data['birthday']);}
                
                $mergedContact = new Contact();
                $mergedContact->setValues($data);
                $mergedContact->setPhones($mergedPhones);
                
                // If mergedContact is OK, we can delete all the duplicates
                if($mergedContact)
                {
                    foreach($contactsWithSameName as $contact)
                        {
                            
                            $contactToRemove = $this->contactRepository->find($contact['id']);
                            if($contactToRemove)
                                {
                                    
                                    $this->contactRepository->remove($contactToRemove);
                                    $this->entityManager->flush();
                                }
                        }
                }
                
                // Persist and flush the entity to save it to the database
                $this->entityManager->persist($mergedContact);
                $this->entityManager->flush();
            }
        }

        // foreach ($usersWithSameName as $name => $users) {
        //     $output->writeln(sprintf('Users with the same name "%s":', $name));

        //     foreach ($users as $user) {
        //         $output->writeln(sprintf('- %s (ID: %d)', $user->getUsername(), $user->getId()));
        //     }

        //     $question = new ConfirmationQuestion('Do you want to merge these users? (yes/no) ', false);

        //     if ($this->getHelper('question')->ask($input, $output, $question)) {
        //         $this->mergeUsers($users);
        //     }
        // }

        $output->writeln('Merge process completed.');

        return Command::SUCCESS;








        }
    }