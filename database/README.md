# Banco de dados do VAM

Os scripts de instalação do VAM 2.6 foram adicionados em `Data_Base_Scritps/` porque não estavam no repositório. Eles vieram da cópia pública [VacationAir/Virtual-Airlines-Manager-VAM](https://github.com/VacationAir/Virtual-Airlines-Manager-VAM), que republica o código do VAM e mantém os arquivos de instalação do banco.

No Plesk, crie primeiro uma base MySQL/MariaDB e um usuário com acesso total somente a essa base. Depois abra o phpMyAdmin dessa base e importe, nesta ordem:

1. `Data_Base_Scritps/db_schema.sql`
2. `Data_Base_Scritps/db_data_1.sql`
3. `Data_Base_Scritps/db_data_2.sql`
4. `Data_Base_Scritps/db_data_3.sql`
5. `Data_Base_Scritps/db_data_4.sql`
6. `Data_Base_Scritps/db_data_5.sql`
7. `database/custom_modules.sql`

Execute `custom_modules.sql` uma vez, depois do esquema e dos dados iniciais do VAM. O script atualiza as tabelas `courses` e `trainings` do VAM e cria as tabelas extras deste fork.

`examenes.sql` e `tutorias.sql` são opcionais e criam tabelas das páginas extras correspondentes. Importe cada um somente se for usar esses módulos. Os scripts grandes de dados incluem aeroportos, frequências, pistas e auxílios de navegação; o phpMyAdmin pode levar alguns minutos para concluir.

Os dados iniciais incluem um administrador de instalação. Troque a senha dele imediatamente após o primeiro acesso. As páginas antigas de cursos, treinamentos e ACARS têm limitações além da estrutura SQL; esta importação cria as tabelas necessárias, mas não corrige os fluxos incompletos dessas páginas.

## Atualizações de bancos existentes

Não importe `db_schema.sql` nem os arquivos `db_data_*.sql` sobre uma base existente: os scripts de dados incluem registros iniciais e não são uma migração. Faça backup antes de qualquer alteração. `custom_modules.sql` deve ser executado uma única vez sobre o esquema VAM 2.6; não o execute novamente depois que as colunas extras tiverem sido criadas.
