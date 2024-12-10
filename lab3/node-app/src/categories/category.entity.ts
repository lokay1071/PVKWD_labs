import {
  Column,
  CreateDateColumn,
  Entity,
  PrimaryGeneratedColumn,
  UpdateDateColumn,
} from 'typeorm';
import { ApiProperty } from '@nestjs/swagger';

@Entity('categories')
export class Category {
  @ApiProperty({
    example: 1,
    description: 'The unique identifier of the category',
  })
  @PrimaryGeneratedColumn()
  id: number;

  @ApiProperty({
    example: 'Electronics',
    description: 'The name of the category',
  })
  @Column()
  name: string;

  @ApiProperty({
    example:
      'Category for all electronic products like phones, laptops, and accessories.',
    description: 'A brief description of the category',
  })
  @Column()
  description: string;

  @ApiProperty({
    example: 'electronics.jpg',
    description: 'The URL of the category image',
  })
  @Column()
  image: string;

  @ApiProperty({
    example: '2023-01-01T12:34:56.789Z',
    description: 'The creation date of the category',
  })
  @CreateDateColumn({
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP(6)',
  })
  public created_at: Date;

  @ApiProperty({
    example: '2023-01-01T12:34:56.789Z',
    description: 'The last update date of the category',
  })
  @UpdateDateColumn({
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP(6)',
    onUpdate: 'CURRENT_TIMESTAMP(6)',
  })
  public updated_at: Date;
}
